<?php
/** @var $this CController */
$this->pageTitle=Yii::app()->name;

$this->widget('CategoriesTreeWidget', array(
	'currentCategoryId' => $currentCategoryId,
));

foreach ($items as $item) {
	echo "<div style='display: inline-block; margin:1em'><a href='{$item['url']}'>";
	if ($item['photoUrl']) {
		echo "<img style='max-width:100px' src='{$item['photoUrl']}'/><br />";
	}
	echo $item['name'];
	echo "</a></div>";
}

?>
