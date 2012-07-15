<?php

require_once(Yii::getPathOfAlias('lib.simplehtmldom.simple_html_dom').'.php');

class GrabInfoCommand extends CConsoleCommand
{
	const HOST = 'http://www.saks.ru';

	private function parseCategory(simple_html_dom $categoryPage, Category $parentCategory) {
		$currentCategoryName = iconv('windows-1251', 'utf8', $categoryPage->find('div.menu_left span.menu_l_s', 0)->plaintext);
		$category = Category::model()->findOrCreate($currentCategoryName, $parentCategory->id);
		$this->grabItemsFromPage($categoryPage, $category);

		//
		// paginator iterator
		//

		// find all visible pages
		$pages = array();
		foreach ($categoryPage->find('td.page div.pager a') as $paginatorLink) {
			$pages[] = self::HOST.$paginatorLink->href;
		}
		// process them
		foreach($pages as $pageLink) {
			$itemsPage = file_get_html($pageLink);
			$this->grabItemsFromPage($itemsPage, $category);
		}
		// process all invisible pages
		if (count($pages) == 7 && preg_match('#(.*?\?p=)(\d+)#', end($pages), $matches)) {
			$offset = $matches[2];
			$offset+= 10;
			$pageLink = $matches[1].$offset;
			$itemsPage = file_get_html($pageLink);
			while($this->grabItemsFromPage($itemsPage, $category)) {
				$offset+= 10;
				$pageLink = $matches[1].$offset;
				$itemsPage = file_get_html($pageLink);
			}
		}

	}

	/**
	 * @param simple_html_dom $itemsPage
	 * @param Category $category
	 * @return int founded items at page
	 */
	private function grabItemsFromPage(simple_html_dom $itemsPage, Category $category) {
		$itemLinks = array();
		foreach ($itemsPage->find('td.page tr.tov_td a') as $itemLink) {
			$itemLinks[] = $itemLink->href;
		}
		$itemLinks = array_unique($itemLinks);
		foreach ($itemLinks as $itemLink)
			$this->parseItemPage($itemLink, $category);

		return count($itemLinks);
	}

	private function parseItemPage($itemPageLink, Category $category) {
		$itemPage = file_get_html(self::HOST.$itemPageLink);
		$infoTd = $itemPage->find('td.page td', -1);
		$infoTd = iconv('windows-1251', 'utf8', (string)$infoTd);

		if (preg_match('#Артикул:.*?<strong>(.*?)</strong>#', $infoTd, $matches)) {
			$item = Item::model()->findOrCreate(trim($matches[1]));
		} else {
			throw new CException('cant parse article from '.$itemPageLink);
		}

		if (preg_match('#<strong>.*?<br />.*?<br />(.*?)</strong>#', $infoTd, $matches)) {
			$item->name = html_entity_decode(trim($matches[1]));
		}

		if (preg_match('#Возраст:.*?<strong>(.*?)<br />#', $infoTd, $matches)) {
			$item->age = html_entity_decode(trim($matches[1]));
		}

		$item->description = iconv('windows-1251', 'utf8', $itemPage->find('td.page strong', -1)->plaintext);
		$item->description = html_entity_decode($item->description);

		$images = array();
		foreach ($itemPage->find('div.card_ps img') as $image) {
			$images[] = $image->src;
		}
		if (empty($images) && ($image = $itemPage->find('td.card_pic img',0))) {
			$images[] = $image->src;
		}
		foreach ($images as $id=>$image) {
			/** @var $fs FileSystem */
			$fs = Yii::app()->fs;

			$imageContents = file_get_contents(self::HOST.$image);
			$ext = CFileHelper::getExtension($image);
			$tmpFile = tempnam(sys_get_temp_dir(), '').'.'.$ext;
			file_put_contents($tmpFile, $imageContents);
			$images[$id] = $fs->publishFile($tmpFile);
			unlink($tmpFile);
		}
		$item->photo = json_encode($images);

		$item->categoryId = $category->id;
		if (!$item->save())
			throw new CException(print_r($item->getErrors(),true));
	}

	public function run() {
		$mainPage = file_get_html(self::HOST.'/section18/');

		// root categories iterator
		foreach ($mainPage->find('div.menu_left a') as $rootCatalogElement) {
			$rootCatalogName = iconv('windows-1251', 'utf8', $rootCatalogElement->plaintext);
			$rootCategory = Category::model()->findOrCreate($rootCatalogName);

			$categoryFirstPage = file_get_html(self::HOST.$rootCatalogElement->href);
			$this->parseCategory($categoryFirstPage, $rootCategory);

			// child categories iterator
			foreach ($categoryFirstPage->find('div.menu_left a.menu_l_a') as $categoryEntry) {
				$categoryPage = file_get_html(self::HOST.$categoryEntry->href);
				$this->parseCategory($categoryPage, $rootCategory);
			}
		}
	}
}
