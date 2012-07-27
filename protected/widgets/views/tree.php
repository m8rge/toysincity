<ul>
<?php
if (!function_exists('printCategory')) {
	function printCategory($tree, $categoriesArray, $_addClass = '', $currentCategoryId) {
		foreach($tree as $id => $child) {
			$addClass = $_addClass;
			if (!empty($categoriesArray[$id]['selected']) && $categoriesArray[$id]['selected'])
				$addClass =' child_current';
			if (!empty($child))
				$url = $categoriesArray[key($child)]['url']; // get url of first child category
			else
				$url = $categoriesArray[$id]['url'];
			echo "<li class='$addClass'><a href='$url'>{$categoriesArray[$id]['name']}</a>";
			if (!empty($child) && array_key_exists($currentCategoryId, $child)) {
	//			echo " <ul>";
				printCategory($child, $categoriesArray, 'child', $currentCategoryId);
	//			echo "</ul>";
			}
			echo "</li>";
		}
	}
}

printCategory($tree, $categoriesArray, 'parent', $currentCategoryId);
?>
</ul>