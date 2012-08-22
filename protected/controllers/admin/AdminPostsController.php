<?php

Yii::import('application.controllers.admin.*');

class AdminPostsController extends AdminController
{
	public $modelName = 'Post';
	public $modelHumanTitle = array('запись', 'записи', 'записей');
	public $allowedActions = 'edit,delete';

	public function getEditFormElements()
	{
		return array(
			'author' => array(
				'type' => 'textField'
			),
			'content' => array(
				'type' => 'textArea'
			),
			'date' => array(
				'type' => 'textField'
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
