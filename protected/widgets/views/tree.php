<ul>
<?php
function printCategory($tree, $categoriesArray) {
	foreach($tree as $id => $child) {
		if (!empty($child)) {
			echo "<li>{$categoriesArray[$id]['name']} <ul>";
			printCategory($child, $categoriesArray);
			echo "</ul></li>";
		} else {
			echo "<li>{$categoriesArray[$id]['name']}</li>";
		}
	}
}
printCategory($tree, $categoriesArray);
?>
</ul>