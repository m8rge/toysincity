<?php

class CategoriesTreeWidget extends CWidget
{
	public function run() {
		$categories = Category::model()->findAll();

		$categoriesArray = array();
		/** @var $category Category */
		foreach($categories as $id => $category) {
			$categoriesArray[$id] = $category->getAttributes();
		}
		$tree = TreeHelper::makeTree(0, $categoriesArray);

		$this->render('tree', array(
			'categories' => $categoriesArray,
			'tree' => $tree,
		));
	}
}
