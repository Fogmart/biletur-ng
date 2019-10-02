<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 *
 * @var \common\components\excursion\CommonExcursion[] $excursions
 */
$rowCount = 0;
$numOfCols = 1;
?>
<div class="row">
	<?php foreach ($excursions as $excursion): ?>
		<div class="col-xs-12">
			<?= $this->render('___single', ['excursion' => $excursion]) ?>
		</div>
		<?php
		$rowCount++;
		if ($rowCount % $numOfCols == 0 && $rowCount < count($excursions)) {
			echo '</div><div class="row">';
		}
		?>
	<?php endforeach ?>
</div>