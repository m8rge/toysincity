<ul>
<?php
function printCategory($tree, $categoriesArray) {
	foreach($tree as $id => $child) {
		$addClass = '';
		if (!empty($categoriesArray[$id]['selected']) && $categoriesArray[$id]['selected'])
			$addClass.=' selected';
		echo "<li class='$addClass'><a href='{$categoriesArray[$id]['url']}'>{$categoriesArray[$id]['name']}</a>";
		if (!empty($child)) {
			echo " <ul>";
			printCategory($child, $categoriesArray);
			echo "</ul>";
		}
		echo "</li>";
	}
}
printCategory($tree, $categoriesArray);
?>
</ul>
<style type="text/css">
	.selected {
		background-color: #aaaaaa;
	}
</style>