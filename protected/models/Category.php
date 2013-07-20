<?php

/**
 * @property int id
 * @property int parentId
 * @property string name
 *
 * @property Category[] childs
 * @property Item[] items
 * @property Category parent
 *
 * @method Category firstLevel
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

    public function attributeLabels()
    {
        return array(
            'name' => 'Название',
            'parentId' => 'Родительская категория',
        );
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
			'items' => array(self::HAS_MANY, 'Item', 'categoryId'),
		);
	}

	private function getAvailableParentIds() {
		$ids = CHtml::listData(Category::model()->findAll(), 'id', 'id');
		unset($ids[$this->id]);
		return $ids;
	}

	public function getUrl() {
		return CHtml::normalizeUrl(array('site/catalog', 'categoryId'=>$this->id));
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

    public function search()
    {
        $criteria=new CDbCriteria;

        $criteria->compare('name', $this->name, true);
        $criteria->compare('parentId', $this->parentId);
        $criteria->order = 'parentId desc';

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 30,
            ),
        ));
    }

    public function scopes()
    {
        return array(
            'firstLevel' => array(
                'condition' => 'parentId IS NULL',
            ),
        );
    }

    protected function afterDelete()
    {
        foreach ($this->childs as $child) {
            $child->delete();
        }

        foreach ($this->items as $item) {
            $item->delete();
        }

    }

}
