<?php

class CategoriesTreeWidget extends CWidget
{
	public $currentCategoryId = null;

	public function run() {
		$categories = Category::model()->findAll();

		$categoriesArray = array();
		/** @var $category Category */
		foreach($categories as $category) {
			$categoriesArray[$category->id] = $category->getAttributes();
			$categoriesArray[$category->id]['url'] = $category->getUrl();
			if ($category->id == $this->currentCategoryId)
				$categoriesArray[$category->id]['selected'] = true;
		}
		$tree = TreeHelper::makeTree(null, CHtml::listData($categories, 'id', 'parentId'));

		$this->render('tree', array(
			'categoriesArray' => $categoriesArray,
			'tree' => $tree[null],
		));
	}
}
