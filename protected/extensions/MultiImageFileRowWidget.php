<?php

class MultiImageFileRowWidget extends CWidget
{
	/** @var CActiveRecord */
	public $model;

	/** @var string refers to fullsize image URL */
	public $attributeName;

	/** @var BootActiveForm */
	public $form;

	/** @var string refers to CUploadedFile instance */
	public $uploadedFileFieldName = '_image';

	/** @var string refers to checkbox field */
	public $removeImageFieldName = '_removeImageFlag';

	/** @var int */
	public $maxImageSize = 120;

	/** @var  */
	public $thumbnailImage = '';

	public $image = '';

	public function run()
	{
		$model = $this->model;
		$attributeName = $this->attributeName;
		$form = $this->form;

		$htmlOptions = array(
			'labelOptions' => array(
				'label' => $model->getAttributeLabel($this->uploadedFileFieldName),
			),
		);
		if (!empty($model->$attributeName) && is_array($model->$attributeName)) {
			foreach ($model->$attributeName as $id => $value) {
				$thumbnail = $value;
				if (!empty($this->thumbnailImage))
					$thumbnail = $this->evaluateExpression($this->thumbnailImage, array('data'=>$model, 'value'=>$value));
				$image = $this->evaluateExpression($this->image, array('data'=>$model, 'value'=>$value));

				$htmlOptions = array_merge($htmlOptions, array(
					'hint' => CHtml::link(
						CHtml::image($thumbnail, '', array('style'=>"max-width:{$this->maxImageSize}px; max-height:{$this->maxImageSize}px")),
						$image,
						array('target' => '_blank')
					),
				));

				echo $form->fileFieldRow($model, $this->uploadedFileFieldName."[$id]", $htmlOptions);
				if (!empty($model->$attributeName)) {
					echo $form->checkboxRow($model, $this->removeImageFieldName."[$id]");
				}
			}
		}
	}

}
