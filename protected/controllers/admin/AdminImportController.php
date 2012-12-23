<?php

Yii::app()->getComponent('bootstrap');

class AdminImportController extends Controller
{
	public function filters()
	{
		return array(
			'accessControl'
		);
	}

	public function accessRules()
	{
		return array(
			array('allow',
				'roles'=>array('admin')
			),
			array('deny',
				'users'=>array('*')
			),
		);
	}

	public function actionIndex()
	{
		$importForm = new ImportForm();
		if(isset($_POST['ImportForm']))
		{
			$importForm->file = CUploadedFile::getInstance($importForm, 'file');
			if ($importForm->validate()) {
				$this->import($importForm->file->tempName);
				unlink($importForm->file->tempName);
			}
		}

		$this->render('//admin/import', array(
			'importForm' => $importForm,
		));
	}

	/**
	 * @param string $fileName
	 * @throws CException
	 */
	public function import($fileName)
	{
		require_once(Yii::app()->basePath.'/../lib/phpExcelReader/Excel/reader.php');
		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('UTF-8');
		$data->setRowColOffset(0);
		$data->read($fileName);

		Item::model()->updateAll(array('display'=>false));
		$sheet = $data->sheets[0];
		for ($i=0; $i<= $sheet['numRows']; $i++) {
			$str = $sheet['cells'][$i];
			if (!empty($str[2])) {
				/** @var $item Item */
				$item = Item::model()->findByAttributes(array('article'=>$str[2]));
				if ($item instanceof Item) {
					$item->display = true;
					$item->scenario = 'import';
					if (!$item->save())
						throw new CException('Не могу сохранить Продукт: '.print_r($item->errors, true));
				}
			}
		}
		Yii::app()->user->setFlash('success', '<strong>Ура!</strong> Я успешно импортировал данные из файла');
	}
}
