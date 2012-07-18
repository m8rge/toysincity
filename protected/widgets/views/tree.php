<ul style="float: left">
<?php
function printCategory($tree, $categoriesArray) {
	foreach($tree as $id => $child) {
		$addClass = '';
		if (!empty($categoriesArray[$id]['selected']) && $categoriesArray[$id]['selected'])
			$addClass.=' selected';
		if (!empty($child))
			$url = $categoriesArray[key($child)]['url']; // get url of first child category
		else
			$url = $categoriesArray[$id]['url'];
		echo "<li class='$addClass'><a href='$url'>{$categoriesArray[$id]['name']}</a>";
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