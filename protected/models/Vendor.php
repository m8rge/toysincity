<?php

/**
 * @property int id
 * @property string name
 */
class Vendor extends CActiveRecord
{
	/**
	 * @static
	 * @param string $className
	 * @return Vendor|CActiveRecord
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function rules()
	{
		return array(
			array('name', 'length', 'max'=>255, 'allowEmpty'=>false),
		);
	}
}
