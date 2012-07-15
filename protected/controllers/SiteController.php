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
		$items = Item::model()->findAllByAttributes(array('categoryId'=>$categoryId));

		$this->render('category', array(
			'items' => RenderHelper::processItems($items),
		));
	}

	public function actionItem($itemId, $categoryId) {
		/** @var $item Item */
		$item = Item::model()->findByPk($itemId);
		if (empty($item) || $item->categoryId != $categoryId)
			throw new CHttpException(404);

		$this->render('item', array(
			'item' => RenderHelper::processItem($item),
		));
	}

	public function actionCart() {
		$cart = Yii::app()->session['cart'];
		$items = array();
		if (is_array($cart))
			$items = Item::model()->findAllByPk(array_keys($cart));

		$this->render('cart', array(
			'cart' => $cart,
			'items' => RenderHelper::processItems($items),
		));
	}

	public function actionAddToCart($itemId) {
		$count = is_numeric($_POST['count']) && $_POST['count']>0 ? $_POST['count'] : 0;

		$cart = Yii::app()->session['cart'];
		if (isset($cart[$itemId]))
			$cart[$itemId]+= $count;
		else
			$cart[$itemId] = $count;

		$this->redirect(array('site/cart'));
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