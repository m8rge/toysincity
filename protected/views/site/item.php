<?php
/** @var $this CController */
$this->pageTitle=Yii::app()->name;
?>
<h1><?php echo $item['name']?></h1>
<?php
if (!empty($item['photo'])) {
	echo "<ul class='fancybox'>";
	foreach ($item['photo'] as $photoUrl) {
		echo "<li><a href='$photoUrl' rel='gallery1'><img style='max-width:100px' src='{$photoUrl}'/></a></li>";
	}
	echo "</ul>";
}
?>

<p>Артикул: <?php echo $item['article']?></p>
<p>Цена: <?php echo $item['price']?></p>
<?php if (!empty($item['age'])) { ?>
<p>Возраст: <?php echo $item['age']?></p>
<?php } ?>
<p><?php echo $item['description']?></p>


<script type="text/javascript">
	(function ($, F) {
		// Next gallery item - fly from left side to the center
		F.transitions.slideIn = function() {
			var endPos = F._getPosition(true);

			endPos.opacity = 0;

			F.wrap.css(endPos).show().animate({
				opacity: 1
			}, {
				duration: F.current.nextSpeed,
				complete: F._afterZoomIn
			});
		};

		// Current gallery item - fly from center to the right
		F.transitions.slideOut = function() {
			F.wrap.removeClass('fancybox-opened').animate({
				opacity: 0
			}, {
				duration: F.current.prevSpeed,
				complete: function () {
					$(this).trigger('onReset').remove();
				}
			});
		};
	}(jQuery, jQuery.fancybox));


	$(document).ready(function() {
		$("ul.fancybox li a").fancybox({
			prevMethod : 'slideOut',
			nextMethod : 'slideIn'
		});
	});
</script>