<?php

Yii::import('application.controllers.admin.*');

class AdminCategoriesController extends AdminController
{
    public $modelName = 'Category';
    public $modelHumanTitle = array('категорию', 'категории', 'категорий');

    public function getEditFormElements($model) {
        return array(
            'name' => array(
                'type' => 'textField'
            ),
            'parentId' => array(
                'type' => 'dropDownList',
                'data' => CHtml::listData(Category::model()->firstLevel()->findAll(), 'id', 'name'),
                'htmlOptions' => array(
                    'empty' => 'Не выбран',
                ),
            ),
        );
    }

    public function getTableColumns()
    {
        $columns = array(
            array(
                'name' => 'parentId',
                'value' => '!empty($data->parent) ? $data->parent->name : ""',
                'header' => 'Родительская категория',
                'filter' => CHtml::listData(Category::model()->firstLevel()->findAll(), 'id', 'name'),
            ),
            'name',
        );
        $columns[]= $this->getButtonsColumn();

        return $columns;
    }
}