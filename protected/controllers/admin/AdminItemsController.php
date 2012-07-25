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
				'name' => 'category.name',
				'filter' => CHtml::listData(Category::model()->findAll(), 'id', 'name'),
			),
		);

		$attributes[] = array(
			'class' => 'bootstrap.widgets.BootButtonColumn',
			'template' => '{update}&nbsp;&nbsp;&nbsp;{delete}',
			'updateButtonUrl' => 'Yii::app()->controller->createUrl("edit",array("id"=>$data->primaryKey))'
		);

		return $attributes;
	}
}
