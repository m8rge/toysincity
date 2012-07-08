<?php

/**
 * @property int id
 * @property string name
 * @property string photo
 * @property string description
 * @property int price
 * @property int categoryId
 * @property int vendorId
 * @property string age
 */
class Item extends CActiveRecord
{
	/**
	 * @static
	 * @param string $className
	 * @return Item|CActiveRecord
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function rules()
	{
		return array(
			array('name', 'required'),
			array('name, photo', 'length', 'max'=>255),
			array('age', 'length', 'max'=>100),
			array('description', 'safe'),
			array('price', 'numerical', 'min'=>0, 'integerOnly'=>true),
			array('categoryId', 'in', 'range' => CHtml::listData(Category::model()->findAll(),'id','id')),
			array('vendorId', 'in', 'range' => CHtml::listData(Vendor::model()->findAll(),'id','id')),
		);
	}

}
