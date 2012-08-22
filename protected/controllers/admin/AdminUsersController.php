<?php

Yii::import('application.controllers.admin.*');

class AdminUsersController extends AdminController
{
	public $modelName = 'User';
	public $modelHumanTitle = array('пользователя', 'пользователя', 'пользователей');

	public function getEditFormElements()
	{
		$authItems = AuthItem::model()->findAll();
		$authItems = CHtml::listData($authItems, 'name', 'name');

		return array(
			'email' => array(
				'type' => 'textField'
			),
			'name' => array(
				'type' => 'textField'
			),
			'phone' => array(
				'type' => 'textField'
			),
			'authItems' => array(
				'type' => 'dropDownList',
				'data' => $authItems,
				'htmlOptions' => array(
					'multiple' => true,
					'size' => 20,
				),
			),
			'password' => array(
				'type' => 'passwordField',
				'htmlOptions' => array(
					'value' => '',
					'hint' => 'Если ничего не вводить, то пароль не будет изменен.',
				),
			),
		);
	}

	public function getTableColumns()
	{
		$columns = array(
			array(
				'name' => 'email',
				'sortable' => false,
			),
			array(
				'name' => 'name',
				'sortable' => false,
			),
			array(
				'name' => 'phone',
				'sortable' => false,
			),
			$this->getButtonsColumn(),
		);

		return $columns;
	}

	/**
	 * @param User $model
	 */
	public function beforeSave($model)
	{
		if (mb_strlen($model->password)<32)
			$model->password = md5($model->password.Yii::app()->params['md5Salt']);;
		parent::beforeSave($model);
	}

	/**
	 * @param User $model
	 * @param array $attributes
	 */
	public function beforeSetAttributes($model, &$attributes)
	{
		if (empty($attributes['password']))
			unset($attributes['password']);

		parent::beforeSetAttributes($model, $attributes);
	}
}
