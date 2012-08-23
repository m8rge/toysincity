<?php

/**
 * @property int id
 * @property string author
 * @property string content
 * @property int date
 * @property int parentId
 *
 * @method Post rootEntries
 */
class Post extends CActiveRecord
{
	public function behaviors()
	{
		return array(
			'createTime' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'date',
				'updateAttribute' => null,
				'timestampExpression' => 'time()',
			),
		);
	}

	/**
	 * @static
	 * @param string $className
	 * @return Post|CActiveRecord
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function attributeLabels()
	{
		return array(
			'author' => 'Имя',
			'content' => 'Сообщение',
			'formattedDate' => 'Дата',
			'date' => 'Дата',
		);
	}

	public function relations()
	{
		return array(
			'childrens' => array(self::HAS_MANY, 'Post', 'parentId'),
		);
	}

	public function rules()
	{
		return array(
			array('author, content', 'required'),
			array('author', 'length', 'max'=>255),
			array('content', 'length', 'max'=>1024),
			array('parentId', 'in', 'range'=>ExtendedHtml::listData(Post::model()->rootEntries())),
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('author', $this->author, true);
		$criteria->compare('content', $this->content, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public function getFormattedDate(){
		return date("H:i d-m-Y", $this->date);
	}

	public function scopes()
	{
		return array(
			'rootEntries' => array(
				'condition' => 'parentId is NULL',
				'order' => 'date desc',
			)
		);
	}
}
