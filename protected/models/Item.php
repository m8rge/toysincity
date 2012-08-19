<?php

/**
 * @property int id
 * @property string name
 * @property array photo
 * @property string description
 * @property int price
 * @property int article
 * @property int categoryId
 * @property int vendorId
 * @property string age
 */
class Item extends CActiveRecord
{
	/** @var array */
	public $_photo;

	/** @var array */
	public $_removeImageFlag;

	/**
	 * @static
	 * @param string $className
	 * @return Item|CActiveRecord
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function behaviors()
	{
		return array(
			'serializedFields' => array(
				'class' => 'application.components.SerializedFieldsBehavior',
				'serializedFields' => array('photo'),
			)
		);
	}

	public function relations()
	{
		return array(
			'category' => array(self::BELONGS_TO, 'Category', 'categoryId'),
			'vendor' => array(self::BELONGS_TO, 'Vendor', 'vendorId'),
		);
	}

	public function rules()
	{
		return array(
			array('name', 'required'),
			array('name', 'length', 'max'=>255),
			array('age', 'length', 'max'=>100),
			array('description, article', 'safe'),
			array('price', 'numerical', 'min'=>0, 'integerOnly'=>true),
			array('categoryId', 'safe', 'on'=>'import'),
			array('categoryId', 'in', 'range' => CHtml::listData(Category::model()->findAll(),'id','id'), 'on'=>'insert, update'),
			array('vendorId', 'in', 'range' => CHtml::listData(Vendor::model()->findAll(),'id','id')),
			array('_photo', 'file', 'types'=>'jpg, gif, png', 'allowEmpty' => true),
			array('_removeImageFlag', 'safe'),
			array('photo', 'safe'),

			array('name, age, article, description, price', 'safe', 'on'=>'search'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'name' => 'Наименование',
			'previewPhotoUrl' => 'Фото',
			'photo' => 'Фото',
			'_removeImageFlag' => 'Удалить фото',
			'age' => 'Возраст',
			'description' => 'Описание',
			'article' => 'Артикул',
			'price' => 'Цена',
			'categoryId' => 'Категория',
			'vendorId' => 'Производитель',
		);
	}

	public function findOrCreate($article) {
		$model = self::model()->findByAttributes(array('article' => $article));
		if (empty($model)) {
			$model = new self;
			$model->article = $article;
			$model->save();
		}

		return $model;
	}

	public function getUrl() {
		return CHtml::normalizeUrl(array('site/item', 'itemId'=>$this->id, 'categoryId'=>$this->categoryId));
	}

	protected function afterDelete()
	{
		/** @var $fs FileSystem */
		$fs = Yii::app()->fs;

		foreach($this->photo as $uid) {
			$fs->removeFile($uid);
		}
	}

	public function getPreviewPhotoUrl() {
		/** @var $fs FileSystem */
		$fs = Yii::app()->fs;

		if (!is_array($this->photo))
			$this->photo = array();
		$photo = reset($this->photo);
		return $fs->getFileUrl($photo);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('name', $this->name, true);
		$criteria->compare('age', $this->age, true);
		$criteria->compare('article', $this->article, true);
		$criteria->compare('description', $this->description, true);
		$criteria->compare('price', $this->price);
		$criteria->compare('categoryId', $this->categoryId);
		$criteria->compare('vendorId', $this->vendorId);

		$criteria->order = 'id desc';

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public function scopes()
	{
		return array(
			'withImages' => array(
				'condition' => 'photo != "[]"',
			),
			'onSite' => array(
				'condition' => 'categoryId != 0 AND price > 0',
			)
		);
	}
}
