<?php

Yii::import('application.controllers.admin.*');

class AdminVendorsController extends AdminController
{
	public $modelName = 'Vendor';
	public $modelHumanTitle = array('производителя', 'производителя', 'производителей');

	public function getEditFormElements() {
		return array(
			'name' => array(
				'type' => 'textField'
			),
			'description' => array(
				'type' => 'textArea',
				'htmlOptions' => array(
					'style' => 'width:500px;height:400px',
				),
			),
		);
	}

	public function getTableColumns()
	{
		$columns = array(
			'name',
		);
		$columns[]= $this->getButtonsColumn();

		return $columns;
	}
}
