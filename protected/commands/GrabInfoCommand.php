<?php

require_once(Yii::getPathOfAlias('lib.simplehtmldom.simple_html_dom').'.php');

class GrabInfoCommand extends CConsoleCommand
{
	const HOST = 'http://www.saks.ru';

	public function run() {
		$mainPage = file_get_html(self::HOST.'/section18/');
		foreach ($mainPage->find('div.menu_left a') as $rootCatalogElement) {
			$rootCatalogName = iconv('windows-1251', 'utf8', $rootCatalogElement->plaintext);
			$rootCategory = Category::model()->findOrCreate($rootCatalogName);
			echo $rootCatalogName."\n";

			$catalogPage = file_get_html(self::HOST.$rootCatalogElement->href);
			$currentCatalogName = iconv('windows-1251', 'utf8', $catalogPage->find('div.menu_left span.menu_l_s', 0)->plaintext);
			$category = Category::model()->findOrCreate($currentCatalogName, $rootCategory->id);
			echo "\t".$currentCatalogName."\n";

			foreach ($catalogPage->find('div.menu_left a.menu_l_a') as $catalogElement) {
				$currentCatalogName = iconv('windows-1251', 'utf8', $catalogElement->plaintext);
				$category = Category::model()->findOrCreate($currentCatalogName, $rootCategory->id);
				echo "\t".$currentCatalogName."\n";
			}
		}
	}
}
