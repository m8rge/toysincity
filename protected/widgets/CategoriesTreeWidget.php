<?php

class CategoriesTreeWidget extends CWidget
{
	public function run() {
		$categories = Category::model()->findAll();

		$categoriesArray = array();
		/** @var $category Category */
		foreach($categories as $category) {
			$categoriesArray[$category->id] = $category->getAttributes();
		}
		$tree = TreeHelper::makeTree(null, CHtml::listData($categories, 'id', 'parentId'));

		$this->render('tree', array(
			'categoriesArray' => $categoriesArray,
			'tree' => $tree[null],
		));
	}
}
