<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 *
 * @var \yii\web\View                      $this
 * @var \common\forms\excursion\SearchForm $form
 */

$numOfCols = 3;
$rowCount = 0;
$bootstrapColWidth = 12 / $numOfCols;
?>
    <div class="result">
    <div class="loading-widget" style="display: none;"></div>
	<?php if (count($form->tags) > 0): ?>
        <div class="tags">
			<?php
			$active = '';
			if (empty($form->cityTag)) {
				$active = ' active';
			}
			?>
            <a href="#" class="tag <?= $active ?>" data-id="">Все экскурсии</a>
			<?php foreach ($form->tags as $tag): ?>
				<?php
				$active = '';
				if ($form->cityTag == $tag->id) {
					$active = ' active';
				}
				?>
                <a href="#" class="tag <?= $active ?>" data-id="<?= $tag->id ?>"><?= $tag->name ?> [<?= $tag->experience_count ?>]</a>
			<?php endforeach; ?>
        </div>
	<?php endif ?>

    <div class="list">
        <div class="row">
			<?php foreach ($form->result as $excursion): ?>
                <div class="col-xs-<?= $bootstrapColWidth ?>">
					<?= $this->render('_single', ['excursion' => $excursion]) ?>
                </div>
				<?php
				$rowCount++;
				if ($rowCount % $numOfCols == 0) {
					echo '</div><div class="row">';
				}
				?>
			<?php endforeach ?>

			<?php if ($form->pageCount > 1 && null !== $form->city): ?>
                <div class="col-xs-12">
                    <ul class="pagination pagination-sm">
						<?php
						for ($i = 0;
							 $i < $form->pageCount;
							 $i++) {
							$active = '';
							if ($form->page == $i + 1) {
								$active = ' active';
							}
							echo '<li class="' . $active . '"><a href="#" class="page-num" data-num="' . ($i + 1) . '">' . ($i + 1) . '</a></li>';
						}
						?>
                    </ul>
                </div>
			<?php endif ?>
        </div>
    </div>
<?php
$this->registerJs('$(".result").searchExcursionPlugin();');
$this->registerJs('$(this).commonPlugin();');
?>