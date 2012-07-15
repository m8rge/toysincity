<?php

require_once(Yii::getPathOfAlias('lib.simplehtmldom.simple_html_dom').'.php');

class GrabInfoCommand extends CConsoleCommand
{
	const HOST = 'http://www.saks.ru';

	private function parseCategoryPage($categoryPage, Category $category) {
		$itemLinks = array();
		foreach ($categoryPage->find('td.page tr.tov_td a') as $itemLink) {
			$itemLinks[] = $itemLink->href;
		}
		$itemLinks = array_unique($itemLinks);
		foreach ($itemLinks as $itemLink)
			$this->parseItemPage($itemLink, $category);
	}

	private function parseItemPage($itemPageLink, Category $category) {
		$itemPage = file_get_html(self::HOST.$itemPageLink);
		$infoTd = $itemPage->find('td.page td', -1);
		$infoTd = iconv('windows-1251', 'utf8', (string)$infoTd);

		if (preg_match('#Артикул:.*?<strong>(.*?)</strong>#', $infoTd, $matches)) {
			$item = Item::model()->findOrCreate($matches[1]);
		} else {
			throw new CException('cant parse article from '.$itemPageLink);
		}

		if (preg_match('#<strong>.*?<br />.*?<br />(.*?)</strong>#', $infoTd, $matches)) {
			$item->name = $matches[1];
		}

		if (preg_match('#Возраст:.*?<strong>(.*?)<br />#', $infoTd, $matches)) {
			$item->age = $matches[1];
		}

		$item->description = $itemPage->find('td.page > strong')->plaintext;

		$images = array();
		foreach ($itemPage->find('div.card_ps img') as $image) {
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
		foreach ($mainPage->find('div.menu_left a') as $rootCatalogElement) {
			$rootCatalogName = iconv('windows-1251', 'utf8', $rootCatalogElement->plaintext);
			$rootCategory = Category::model()->findOrCreate($rootCatalogName);
//			echo $rootCatalogName."\n";

			// first page parse
			$categoryFirstPage = file_get_html(self::HOST.$rootCatalogElement->href);
			$currentCategoryName = iconv('windows-1251', 'utf8', $categoryFirstPage->find('div.menu_left span.menu_l_s', 0)->plaintext);
			$category = Category::model()->findOrCreate($currentCategoryName, $rootCategory->id);
//			echo "\t".$currentCategoryName."\n";
			$this->parseCategoryPage($categoryFirstPage, $category);

			// paginator iterator
			foreach ($categoryFirstPage->find('td.page div.pager a') as $paginatorLink) {
				$categoryPage = file_get_html(self::HOST.$paginatorLink->href);
				$this->parseCategoryPage($categoryPage, $category);
			}

			// categories iterator
			foreach ($categoryFirstPage->find('div.menu_left a.menu_l_a') as $categoryEntry) {
				$currentCategoryName = iconv('windows-1251', 'utf8', $categoryEntry->plaintext);
				$category = Category::model()->findOrCreate($currentCategoryName, $rootCategory->id);
//				echo "\t".$currentCategoryName."\n";
				$this->parseCategoryPage($categoryFirstPage, $category);

				// paginator iterator
				foreach ($categoryFirstPage->find('td.page div.pager a') as $paginatorLink) {
					$categoryPage = file_get_html(self::HOST.$paginatorLink->href);
					$this->parseCategoryPage($categoryPage, $category);
				}
			}
		}
	}
}
