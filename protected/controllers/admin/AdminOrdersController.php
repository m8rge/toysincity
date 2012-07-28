<?php

Yii::import('application.controllers.admin.*');

class AdminOrdersController extends AdminController
{
	public $modelName = 'Order';
	public $modelHumanTitle = array('заказ', 'заказа', 'заказов');

	public function getEditFormElements($model) {
		return array(
			'id' => array(
				'type' => 'uneditable'
			),
			'formattedCreatedDate' => array(
				'type' => 'uneditable',
			),
			'address' => array(
				'type' => 'uneditable',
			),
			'date' => array(
				'type' => 'uneditable',
			),
			'userName' => array(
				'type' => 'uneditable',
			),
			'userEmail' => array(
				'type' => 'uneditable',
			),
			'userPhone' => array(
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
				'name' => 'address',
				'sortable' => false,
			),
			array(
				'name' => 'date',
				'sortable' => false,
			),
			array(
				'name' => 'userName',
				'sortable' => false,
			),
			array(
				'name' => 'userEmail',
				'sortable' => false,
			),
			array(
				'name' => 'userPhone',
				'sortable' => false,
			),
//			array(
//				'name' => 'userId',
//				'value' => '$data->user->email',
//				'sortable' => false,
//			),
			array(
				'name' => 'created',
				'filter' => false,
				'value' => '$data->getFormattedCreatedDate()',
			),
			$this->getButtonsColumn(),
		);

		return $columns;
	}
}
