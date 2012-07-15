<?php
var_dump($tree);
?>
<ul>
<?php
foreach ($categories as $category) {
	echo "<li>{$category['name']}</li>";
}
?>
</ul>