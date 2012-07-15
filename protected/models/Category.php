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

	public function getUrl() {
		return CHtml::normalizeUrl(array('site/category', 'categoryId'=>$this->id));
	}

	public function findOrCreate($name, $parentId = null) {
		$findArray = array('name'=>$name);
		if (!empty($parentId))
			$findArray['parentId'] = $parentId;
		$model = self::model()->findByAttributes($findArray);
		if (empty($model)) {
			$model = new self;
			$model->name = $name;
			if (!empty($parentId))
				$model->parentId = $parentId;
			$model->save();
		}

		return $model;
	}
}
