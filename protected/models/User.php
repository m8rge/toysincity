<?php

/**
 * @property int id
 * @property string email
 * @property string name
 * @property string phone
 * @property string password
 */
class User extends CActiveRecord
{
	/**
	 * @static
	 * @param string $className
	 * @return User
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function behaviors()
	{
		return array(
			'manyToMany' => array(
				'class' => 'lib.ar-relation-behavior.EActiveRecordRelationBehavior',
			),
		);
	}

	public function attributeLabels()
	{
		return array(
			'email' => 'E-mail',
			'name' => 'Имя',
			'phone' => 'Телефон',
			'password' => 'Пароль',
			'authItems' => 'Права',
		);
	}

	public function relations()
	{
		return array(
			'authItems' => array(self::MANY_MANY, 'AuthItem', 'AuthAssignment(userid, itemname)'),
		);
	}

	public function rules()
	{
		return array(
			array('email', 'required'),
			array('email', 'email'),
			array('email', 'unique'),
			array('password', 'required', 'on'=>'insert'),
			array('password', 'length', 'max'=>31, 'on'=>'insert,update'),
			array('password', 'length', 'is'=>32, 'allowEmpty'=>false, 'on'=>'save'),
			array('name, phone', 'safe'),

			array('email', 'safe', 'on'=>'search'),
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('email', $this->email, true);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('phone', $this->phone, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}
