<?php

Yii::import('application.controllers.admin.*');

class AdminOrdersController extends AdminController
{
	public $modelName = 'Order';
	public $modelHumanTitle = array('заказ', 'заказ', 'заказов');

	public function getEditFormElements($model) {
		return array(
			'id' => array(
				'type' => 'uneditable'
			),
			'formattedCreatedDate' => array(
				'type' => 'uneditable',
			),
			'orderText' => array(
				'type' => 'uneditable',
				'htmlOptions' => array(
					'style'=>'width:400px',
				),
			),
		);
	}

	public function getTableColumns() {
		$columns = array(
			'id',
			array(
				'name' => 'userId',
				'value' => '$data->user->email',
				'sortable' => false,
			),
			array(
				'name' => 'created',
				'value' => '$data->getFormattedCreatedDate()',
			),
			$this->getButtonsColumn(),
		);

		return $columns;
	}
}
