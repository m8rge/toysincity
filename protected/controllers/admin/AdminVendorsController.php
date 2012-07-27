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
		);
	}
}
