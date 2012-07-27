<?php

class TreeHelper
{
	static function makeTree($root, $inputs)
	{
		// обращаем отношение: из Child->Parent делаем Parent->*Child

		// лес деревьев для каждого элемента
		$nodes = array(); // id => array(...)

		// сперва каждому элементу присваиваем пустой лес
		foreach($inputs as $child=>$parent) {
			$nodes[$child] = array();
			$nodes[$parent] = array();
		}

		// затем добавляем пару (элемент => ссылка на его лес) в лес родителя
		foreach($inputs as $child=>$parent) {
			$nodes[$parent][$child] = & $nodes[$child];
		}

		// и, наконец, создаём дерево - корневой элемент и его лес или если не можем построить корректное дерево
		// вернем пустой результат
		$tree = isset($nodes[$root]) ? array($root => $nodes[$root]) : array( $root => array());

		return $tree;
	}

	static function getTreeForDropDownBox($models, $canSelectRoots = false, $fieldNames = array('id'=>'id', 'name'=>'name', 'parentId'=>'parentId')) {
		$categoriesArray = CHtml::listData($models, $fieldNames['id'], $fieldNames['name']);
		$tree = TreeHelper::makeTree(null, CHtml::listData($models, $fieldNames['id'], $fieldNames['parentId']));

		$result = array();
		foreach ($tree[null] as $id => $child) {
			if ($canSelectRoots)
				$result[$id] = $categoriesArray[$id];
			foreach ($child as $_id => $_child) {
				if ($canSelectRoots) {
					$result[$_id] = '- - '.$categoriesArray[$_id];
				} else {
					$result[ $categoriesArray[$id] ][$_id] = $categoriesArray[$_id];
				}
			}
		}

		return $result;
	}
}
