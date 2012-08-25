<?php

class MarkitupWidget extends CWidget
{
	/** @var CActiveRecord */
	public $model;

	/** @var string refers to fullsize image URL */
	public $attributeName;

	/** @var BootActiveForm */
	public $form;

	/** @var string url to published ./web folder*/
	protected $url;

	public function init()
	{
		parent::init();
		$this->url = Yii::app()->assetManager->publish(__DIR__ . '/web', false, -1, YII_DEBUG);
		Yii::app()->clientScript->registerScriptFile($this->url . '/markitup/markitup/jquery.markitup.js');

		Yii::app()->clientScript->registerCssFile($this->url . '/skin/style.css');

		Yii::app()->clientScript->registerCssFile($this->url . '/bbcode-set/style.css');
		Yii::app()->clientScript->registerScriptFile($this->url . '/bbcode-set/set.js');
		Yii::app()->clientScript->registerScriptFile($this->url . '/bbcode-set/parser.js');
	}

	public function run()
	{
		echo $this->form->textAreaRow($this->model, $this->attributeName);
		$id = ExtendedHtml::resolveId($this->model, $this->attributeName);
		$id = str_replace("'","\\'", $id);
		Yii::app()->clientScript->registerScript('markitup', "
			$('#$id').markItUp($.extend(mySettings,{
				root: '$this->url/',
				previewParser: previewBBCode,
			}));
		");
	}
}
