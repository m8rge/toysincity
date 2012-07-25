<?php

Yii::import('application.controllers.admin.*');

class AdminItemsController extends AdminController
{
	public $modelName = 'Item';
	public $modelHumanTitle = array('продукт', 'продукта', 'продуктов');

	public function getEditFormElements() {
		return array(
			'name' => array(
				'type' => 'textField'
			),
		);
	}

	public function getTableColumns() {
		$categories = Category::model()->findAll();
		/** @var $category Category */
		$categoriesArray = CHtml::listData($categories, 'id', 'name');
		$tree = TreeHelper::makeTree(null, CHtml::listData($categories, 'id', 'parentId'));

		$categoriesFilter = array();
		foreach ($tree[null] as $id => $child) {
			foreach ($child as $_id => $_child) {
				$categoriesFilter[ $categoriesArray[$id] ][$_id] = $categoriesArray[$_id];
			}
		}

		$attributes = array(
			'article',
			'name',
			'price',
			array(
				'name' => 'categoryId',
				'value' => '($data->category->parent ? $data->category->parent->name : "")." / ".$data->category->name',
				'filter' => $categoriesFilter,
				'sortable' => false,
			),
			array(
				'name' => 'vendorId',
				'value' => '$data->vendor->name',
				'filter' => CHtml::listData(Vendor::model()->findAll(), 'id', 'name'),
				'sortable' => false,
			),
			$this->getButtonsColumn(),
		);

		return $attributes;
	}
}
