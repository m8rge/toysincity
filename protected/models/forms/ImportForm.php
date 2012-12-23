<?php

class ImportForm extends CFormModel
{
	/**
	 * @var CUploadedFile
	 */
	public $file;

	public function rules() {
		return array(
			array('file', 'file', 'types'=>'xls', 'allowEmpty'=>false),
		);
	}

	public function attributeLabels()
	{
		return array(
			'file'=>'Файл для импорта',
		);
	}
}
