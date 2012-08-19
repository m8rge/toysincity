<?php

Yii::import('application.controllers.admin.*');

class AdminStaticPagesController extends AdminController
{
	public $modelName = 'StaticPage';
	public $modelHumanTitle = array('статическую страницу', 'статической страницы', 'статических страниц');
	public $allowedActions = 'edit';

	public function init()
	{
		parent::init();

		$url = Yii::app()->assetManager->publish(Yii::getPathOfAlias('lib.markitup.markitup'));
		Yii::app()->clientScript->registerScriptFile($url . '/jquery.markitup.js');

		Yii::app()->clientScript->registerCssFile($url . '/skins/simple/style.css');

		Yii::app()->clientScript->registerCssFile($url . '/sets/default/style.css');
		Yii::app()->clientScript->registerScriptFile($url . '/sets/default/set.js');
	}

	public function getEditFormElements($model) {
		$textAreaId = ExtendedHtml::resolveId($model, 'content');
		Yii::app()->clientScript->registerScript('markitup', "$('#$textAreaId').markItUp(mySettings);");
		return array(
			'title' => array(
				'type' => 'textField'
			),
			'content' => array(
				'type' => 'textArea',
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
