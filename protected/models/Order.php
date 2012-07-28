<?php

/**
 * @property int id
 * @property int userId
 * @property string order
 * @property string address
 * @property string date
 * @property string userEmail
 * @property string userName
 * @property string userPhone
 * @property int created
 */
class Order extends CActiveRecord
{
	public function behaviors()
	{
		return array(
			'createTime' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'created',
				'updateAttribute' => null,
				'timestampExpression' => 'time()',
			),
		);
	}

	public function relations()
	{
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'userId'),
		);
	}

	/**
	 * @static
	 * @param string $className
	 * @return Order
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function init()
	{
		$this->scenario = 'save';
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'Номер заказа',
			'userId' => 'Пользователь',
			'address' => 'Адрес доставки',
			'date' => 'Дата доставки',
			'orderText' => 'Содержание заказа',
			'userName' => 'Имя заказчика',
			'userEmail' => 'E-mail заказчика',
			'userPhone' => 'Телефон заказчика',
			'created' => 'Дата создания',
			'formattedCreatedDate' => 'Дата создания',
		);
	}

	public function rules()
	{
		return array(
			array('userEmail', 'email', 'allowEmpty'=>false),
			array('created', 'numerical', 'integerOnly' => true),
			array('userId', 'in', 'range' => CHtml::listData(User::model()->findAll(),'id','id')),
			array('address, date, order', 'safe'),
			array('userName, userEmail, userPhone', 'required'),

			array('id, created, userId', 'safe', 'on'=>'search'),
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('created', $this->created);
		$criteria->compare('address', $this->address, true);
		$criteria->compare('userName', $this->userName, true);
		$criteria->compare('userEmail', $this->userEmail, true);
		$criteria->compare('userPhone', $this->userPhone, true);
		$criteria->compare('date', $this->date, true);
//		$criteria->with = array('user');
//		$criteria->compare('user.email', $this->userId, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public function getFormattedCreatedDate(){
		return date("H:i d-m-Y",$this->created);
	}

	public function getOrderText() {
		$order = json_decode($this->order, true);
		if (!is_array($order))
			$order = array();
		$text = '';
		foreach($order as $itemId=>$count)
			$text.=Item::model()->findByPk($itemId)->name." - $count шт. <br>";
		return $text;
	}
}
