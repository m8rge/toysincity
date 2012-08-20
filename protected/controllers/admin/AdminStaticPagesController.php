<?php

Yii::import('application.controllers.admin.*');

class AdminStaticPagesController extends AdminController
{
	public $modelName = 'StaticPage';
	public $modelHumanTitle = array('статическую страницу', 'статической страницы', 'статических страниц');
	public $allowedActions = 'edit';

	public function getEditFormElements($model) {
		return array(
			'title' => array(
				'type' => 'textField'
			),
			array(
				'class' => 'ext.markitup.MarkitupWidget',
				'name' => 'content',
			),
		);
	}

	public function getTableColumns()
	{
		$columns = array(
			'title',
		);
		$columns[]= $this->getButtonsColumn();

		return $columns;
	}
}
