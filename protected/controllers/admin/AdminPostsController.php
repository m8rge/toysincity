<?php

Yii::import('application.controllers.admin.*');

class AdminPostsController extends AdminController
{
	public $modelName = 'Post';
	public $modelHumanTitle = array('запись', 'записи', 'записей');
	public $allowedActions = 'view,delete';

	public function getEditFormElements($model)
	{
		return array(
			'author' => array(
				'type' => 'uneditable'
			),
			'content' => array(
				'type' => 'uneditable'
			),
			'formattedDate' => array(
				'type' => 'uneditable',
			),
		);
	}

	public function getTableColumns()
	{
		$columns = array(
			array(
				'name' => 'author',
				'sortable' => false,
			),
			array(
				'name' => 'content',
				'sortable' => false,
			),
			array(
				'name' => 'date',
				'filter' => false,
				'value' => '$data->getFormattedDate()',
			),
			$this->getButtonsColumn(),
		);

		return $columns;
	}
}
