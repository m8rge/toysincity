<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	/*public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}*/

	public function actionCatalog($categoryId) {
		/** @var $fs FileSystem */
		$fs = Yii::app()->fs;

		$items = Item::model()->findAllByAttributes(array('categoryId'=>$categoryId));

		/** @var $item Item */
		foreach($items as $id => $item) {
			$items[$id] = $item->getAttributes();
			$items[$id]['photo'] = json_decode($items[$id]['photo'], true);
			$items[$id]['photoUrl'] = reset($items[$id]['photo']);
			$items[$id]['photoUrl'] = $fs->getFileUrl($items[$id]['photoUrl']);
			$items[$id]['url'] = $item->getUrl();
		}
		$this->render('category', array(
			'items' => $items,
		));
	}

	public function actionItem($itemId, $categoryId) {
		/** @var $item Item */
		$item = Item::model()->findByPk($itemId);
		if (empty($item) || $item->categoryId != $categoryId)
			throw new CHttpException(404);

		/** @var $fs FileSystem */
		$fs = Yii::app()->fs;

		$item = $item->getAttributes();
		$item['photo'] = json_decode($item['photo'], true);
		foreach($item['photo'] as $id => $uid) {
			$item['photo'][$id] = $fs->getFileUrl($uid);
		}

		$this->render('item', array(
			'item' => $item,
		));
	}

	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='LoginForm')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->getReturnUrl(array('site/index')));
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}