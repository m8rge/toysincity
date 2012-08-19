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

	public function attributeLabels()
	{
		return array(
			'name' => 'Название',
		);
	}
	public function rules()
	{
		return array(
			array('name', 'required'),
			array('name', 'length', 'max'=>255, 'allowEmpty'=>false),
		);
	}
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('name', $this->name, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public function defaultScope()
	{
		return array(
			'order' => 'name',
		);
	}
}
