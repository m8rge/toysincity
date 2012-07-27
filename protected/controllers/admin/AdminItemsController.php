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
		$attributes = array(
			'article',
			'name',
			'price',
			array(
				'name' => 'categoryId',
				'value' => '($data->category->parent ? $data->category->parent->name : "")." / ".$data->category->name',
				'filter' => TreeHelper::getTreeForDropDownBox(Category::model()->findAll()),
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
