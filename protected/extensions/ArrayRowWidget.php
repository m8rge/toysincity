<?php

class ArrayRowWidget extends CWidget
{
	/** @var CActiveRecord */
	public $model;

	/** @var string refers to fullsize image URL */
	public $attributeName;

	/** @var BootActiveForm */
	public $form;

	/** @var string class name for using */
	public $class;

	public function run(){
		$model = $this->model;
		$attributeName = $this->attributeName;
		$form = $this->form;

		foreach($model->$attributeName as $item) {
			$c = Yii::app()->getController();
			$c->widget($this->class, array(
				'model' => $model,
				'form' => $form,
				'attributeName' => $attributeName,
				'value' => $item,
			));
		}
	}
}
