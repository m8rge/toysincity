<?php

/**
 * @property int id
 * @property string name
 * @property string description
 *
 * @property Item[] items
 */
class Vendor extends CActiveRecord
{
	/**
	 * @static
	 * @param string $className
	 * @return Vendor
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

    public function relations()
    {
        return array(
            'items' => array(self::HAS_MANY, 'Item', 'vendorId'),
        );
    }

    public function attributeLabels()
	{
		return array(
			'name' => 'Название',
			'description' => 'Описание',
		);
	}
	public function rules()
	{
		return array(
			array('name', 'required'),
			array('name', 'length', 'max'=>255, 'allowEmpty'=>false),
			array('description', 'safe'),
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

    protected function afterDelete()
    {
        foreach ($this->items as $item) {
            $item->delete();
        }
    }
}
