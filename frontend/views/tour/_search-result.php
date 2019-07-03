<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 *
 * @var \yii\web\View                        $this
 * @var \common\components\tour\CommonTour[] $tours
 *
 */

?>
	<div class="result">
		<div class="loading-widget" style="display: none;"></div>
		<?php foreach ($tours as $tour): ?>
			<div class="row">
				<div class="col-xs-12">
					<div class="tour-item">
						<h4><?= $tour->title ?></h4>
						<?php if (null !== $tour->image):?>
							<?= Yii::$app->imageCache->thumb($tour->image, 'medium', ['class' => 'img-rounded']) ?>
						<?php else:?>
							<img src="http://biletur.ru<?= $tour->imageOld ?>">
						<?php endif ?>
					</div>
				</div>
			</div>
		<?php endforeach ?>
	</div>
<?php
$this->registerJs('$(this).searchTourPlugin();');
?>