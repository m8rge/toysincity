<?php

/**
 * @property int id
 * @property string uri
 * @property string title
 * @property string content
 */
class StaticPage extends CActiveRecord
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
			'uri' => 'Часть ссылки',
			'title' => 'Заголовок',
			'content' => 'Содержание',
		);
	}

	public function rules()
	{
		return array(
			array('uri, title', 'required'),
			array('title, uri', 'length', 'max'=>255, 'allowEmpty'=>false),
			array('content', 'safe'),
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('uri', $this->uri, true);
		$criteria->compare('title', $this->title, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public function getContent(){
		return BBCodeParser::parse($this->content);
	}
}
