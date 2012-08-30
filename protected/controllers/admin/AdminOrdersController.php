<?php

Yii::import('application.controllers.admin.*');

class AdminOrdersController extends AdminController
{
	public $modelName = 'Order';
	public $modelHumanTitle = array('заказ', 'заказа', 'заказов');
	public $allowedActions = 'edit,delete';

	public function getEditFormElements($model) {
		return array(
			'id' => array(
				'type' => 'uneditable'
			),
			'formattedCreatedDate' => array(
				'type' => 'uneditable',
			),
			'city' => array(
				'type' => 'uneditable',
			),
			'street' => array(
				'type' => 'uneditable',
			),
			'house' => array(
				'type' => 'uneditable',
			),
			'apartment' => array(
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
			'status' => array(
				'type' => 'dropDownList',
				'data' => Order::model()->getStatuses(),
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
				'name' => 'city',
				'sortable' => false,
			),
			array(
				'name' => 'street',
				'sortable' => false,
			),
			array(
				'name' => 'house',
				'sortable' => false,
			),
			array(
				'name' => 'apartment',
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
			array(
				'name' => 'status',
				'value' => '$data->getStatusName()',
				'filter' => Order::model()->getStatuses(),
			),
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
