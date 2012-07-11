<?php

/**
 * @property int id
 * @property int parentId
 * @property string name
 *
 * @property array childs
 * @property Category parent
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

	public function relations()
	{
		return array(
			'parent' => array(self::BELONGS_TO, 'Category', 'parentId'),
			'childs' => array(self::HAS_MANY, 'Category', 'parentId'),
		);
	}

	private function getAvailableParentIds() {
		$ids = CHtml::listData(Category::model()->findAll(), 'id', 'id');
		unset($ids[$this->id]);
		return $ids;
	}
}
