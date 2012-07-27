<?php

/**
 * @property int id
 * @property int userId
 * @property string order
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
			'orderText' => 'Содержание заказа',
			'created' => 'Дата создания',
			'formattedCreatedDate' => 'Дата создания',
		);
	}

	public function rules()
	{
		return array(
			array('created', 'numerical', 'integerOnly' => true,  'allowEmpty'=>false),
			array('userId', 'in', 'range' => CHtml::listData(User::model()->findAll(),'id','id')),


			array('id, created, userId', 'safe', 'on'=>'search'),
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('userId', $this->userId);
		$criteria->compare('created', $this->created);

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
