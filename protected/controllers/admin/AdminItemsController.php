<?php

Yii::import('application.controllers.admin.*');

class AdminItemsController extends AdminController
{
	public $modelName = 'Item';
	public $modelHumanTitle = array('продукт', 'продукта', 'продуктов');

	public function getEditFormElements() {
		return array(
			'name' => array(
				'type' => 'textField'
			),
			'description' => array(
				'type' => 'textArea',
				'htmlOptions' => array(
					'style'=>'width:300px;height:100px',
				)
			),
			'categoryId' => array(
				'type' => 'dropDownList',
				'data' => TreeHelper::getTreeForDropDownBox(Category::model()->findAll()),
				'htmlOptions' => array(
					'empty' => 'Не выбрана',
				),
			),
			'vendorId' => array(
				'type' => 'dropDownList',
				'data' => CHtml::listData(Vendor::model()->findAll(), 'id', 'name'),
				'htmlOptions' => array(
					'empty' => 'Не выбран',
				),
			),
			'price' => array(
				'type' => 'textField',
			),
			'article' => array(
				'type' => 'textField',
			),
			'age' => array(
				'type' => 'textField',
			),
			array(
				'name' => 'photo',
				'class' => 'ext.MultiImageFileRowWidget',
				'options' => array(
					'thumbnailImage' => 'Yii::app()->fs->getFileUrl($value)',
					'image' => 'Yii::app()->fs->getFileUrl($value)',
					'uploadedFileFieldName' => '_photo',
				),
			)
		);
	}

	public function getTableColumns() {
		$attributes = array(
			array(
				'name' => 'previewPhotoUrl',
				'class' => 'ext.BootImageColumn',
			),
			'article',
			'name',
			'price',
			array(
				'name' => 'categoryId',
				'value' => '($data->category->parent ? $data->category->parent->name : "")." / ".$data->category->name',
				'filter' => TreeHelper::getTreeForDropDownBox(Category::model()->findAll()),
				'sortable' => false,
			),
			array(
				'name' => 'vendorId',
				'value' => '$data->vendor->name',
				'filter' => CHtml::listData(Vendor::model()->findAll(), 'id', 'name'),
				'sortable' => false,
			),
			$this->getButtonsColumn(),
		);

		return $attributes;
	}

	/**
	 * @param Item $model
	 */
	public function beforeSave($model)
	{
		/** @var $fs FileSystem */
		$fs = Yii::app()->fs;
		foreach ($model->_removeImageFlag as $id => $remove) {
			if ($remove) {
				$fs->removeFile($model->photo[$id]);
				$photos = $model->photo;
				unset($photos[$id]);
				$model->photo = $photos;
			}
		}
		if (is_array($model->_photo)) {
			foreach ($model->_photo as $key => $file) {
				$file = CUploadedFile::getInstance($model, '_photo[' . $key . ']');
				if (!is_null($file)) {
					$photos = $model->photo;
					$photos[] = $fs->publishFile($file->tempName, $file->name);
					$model->photo = $photos;
				}
			}
		}
		parent::beforeSave($model);
	}
}
