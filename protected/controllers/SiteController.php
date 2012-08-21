<?php

class SiteController extends Controller
{
//	public $layout = '//layouts/main';

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
		$items = Item::model()->onSite()->findAllByAttributes(array('categoryId'=>$categoryId));
		if (empty($items))
			throw new CHttpException(404);

		$this->render('category', array(
			'items' => RenderHelper::processItems($items),
			'currentCategoryId' => $categoryId,
			'category' => Category::model()->findByPk($categoryId),
		));
	}

	public function actionItem($itemId, $categoryId) {
		/** @var $item Item */
		$item = Item::model()->findByPk($itemId);
		if (empty($item) || $item->categoryId != $categoryId)
			throw new CHttpException(404);

		$this->render('item', array(
			'item' => RenderHelper::processItem($item),
			'category' => $item->category,
			'vendorLink' => CHtml::link($item->vendor->name, array('/site/vendor', 'vendor'=>$item->vendorId)),
		));
	}

	public function actionCart() {
		$cart = Yii::app()->session['cart'];
		$items = array();
		if (is_array($cart))
			$items = Item::model()->findAllByPk(array_keys($cart));

		$order = new Order();
		if(isset($_POST['Order']))
		{
			$order->attributes=$_POST['Order'];
			$order->order = json_encode($cart);
			// validate user input and redirect to the previous page if valid
			if($order->validate() && $order->save()) {
				Yii::app()->session['cart'] = array();
				MailHelper::sendTextMail('toysincity.ru','robot@toysincity.ru', Yii::app()->params['adminEmail'],'Заказ '.$order->id,
"Имя: {$order->userName}
Телефон: {$order->userPhone}
email: {$order->userEmail}
Дата доставки: {$order->date}
Адрес доставки: {$order->address}
Дата создания заказа: {$order->getFormattedCreatedDate()}
Заказ: {$order->getOrderText("\n")}");
				$this->redirect(array('site/order'));
			}
		}

		$this->render('cart', array(
			'cart' => $cart,
			'items' => RenderHelper::processIdItems($items),
			'order' => $order,
		));
	}

	public function actionOrder(){
		$this->render('order');
	}

	public function actionAddToCart($itemId) {
		$count = is_numeric($_POST['count']) && $_POST['count']>0 ? $_POST['count'] : 0;

		$cart = Yii::app()->session['cart'];
		if (isset($cart[$itemId]))
			$cart[$itemId]+= $count;
		else
			$cart[$itemId] = $count;

		Yii::app()->session['cart'] = $cart;

		$this->redirect(array('site/cart'));
	}

	public function actionChangeCart(){
		if ($_POST['recalculate']) {
			$cart = array();
			foreach($_POST['count'] as $itemId => $count) {
				if ($count > 0)
					$cart[$itemId] = $count;
			}
			Yii::app()->session['cart'] = $cart;

			$this->redirect(array('site/cart'));
		} else if ($_POST['removeFromCart']) {
			$cart = Yii::app()->session['cart'];
			foreach($cart as $itemId => $count) {
				if ($itemId == $_POST['removeItemId'])
					unset($cart[$itemId]);
			}
			Yii::app()->session['cart'] = $cart;

			$this->redirect(array('site/cart'));
		}
	}

	public function actionIndex()
	{
		$items = Item::model()->onSite()->withImages()->findAll(array(
			'order' => 'RAND()',
			'limit' => 10,
		));

		$this->render('index', array(
			'items' => RenderHelper::processItems($items),
		));
	}

	public function actionSearch() {
		$items = Item::model();
		if (!empty($_GET['searchstring']))
			$items->dbCriteria
				->compare('name', $_GET['searchstring'], true)
				->compare('article', $_GET['searchstring'], true, 'OR');
		if (!empty($_GET['ageFrom']))
			$items->dbCriteria
				->compare('ageTo', '>='.$_GET['ageFrom']);
		if (!empty($_GET['ageTo']))
			$items->dbCriteria
				->compare('ageFrom', '<='.$_GET['ageTo']);
		if (!empty($_GET['vendor']))
			$items->dbCriteria
				->compare('vendorId', $_GET['vendor']);

		$items->onSite();

		$criteria = $items->dbCriteria;
		$pager = new CPagination($items->count());
		$items->dbCriteria = $criteria;
		$pager->applyLimit($items->dbCriteria);

		$this->render('search', array(
			'items' => RenderHelper::processItems($items->findAll()),
			'pager' => $pager,
		));
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

	public function actionVendor(){
		$vendor = Vendor::model()->findByPk($_GET['vendor']);
		$this->render('vendor',array('vendor'=>$vendor));
	}

	public function actionStaticPage($uri){
		$page = StaticPage::model()->findByAttributes(array('uri'=>$uri));
		$this->render('staticPage',array('page'=>$page));
	}
}