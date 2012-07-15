<?php

class TreeHelper
{
	static function makeTree($root, $inputs, $parentFieldName = 'parentId')
	{
		// обращаем отношение: из Child->Parent делаем Parent->*Child

		// лес деревьев для каждого элемента
		$nodes = array(); // id => array(...)

		// сперва каждому элементу присваиваем пустой лес
		foreach($inputs as $child=>$input) {
			$nodes[$child] = array();
			$nodes[$input[$parentFieldName]] = array();
		}

		// затем добавляем пару (элемент => ссылка на его лес) в лес родителя
		foreach($inputs as $child=>$input) {
			$nodes[$input[$parentFieldName]][$child] = & $nodes[$child];
		}

		// и, наконец, создаём дерево - корневой элемент и его лес или если не можем построить корректное дерево
		// вернем пустой результат
		$tree = isset($nodes[$root]) ? array($root => $nodes[$root]) : array( $root => array());

		return $tree;
	}
}
