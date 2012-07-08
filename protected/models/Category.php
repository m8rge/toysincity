<?php

/**
 * @property int id
 * @property int parentId
 * @property string name
 */
class Category extends CActiveRecord
{
	/**
	 * @static
	 * @param string $className
	 * @return Category|CActiveRecord
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function rules()
	{
		return array(
			array('name', 'length', 'max'=>255, 'allowEmpty'=>false),
			array('parentId', 'in', 'range'=>$this->getAvailableParentIds()),
		);
	}

	private function getAvailableParentIds() {
		$ids = CHtml::listData(Category::model()->findAll(), 'id', 'id');
		unset($ids[$this->id]);
		return $ids;
	}
}
