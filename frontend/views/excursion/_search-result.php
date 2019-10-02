<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 *
 * @var \yii\web\View                      $this
 * @var \common\forms\excursion\SearchForm $form
 */
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
		<div class="list excursion-list">
			<?= $this->render('__excursions', ['excursions' => $form->result]) ?>
		</div>
	</div>
<?php
$this->registerJs('$(".result").searchExcursionPlugin();');
$this->registerJs('$(this).commonPlugin();');
?>