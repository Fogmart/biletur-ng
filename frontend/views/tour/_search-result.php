<?php

use common\components\RemoteImageCache;
use frontend\controllers\TourController;
use common\base\helpers\StringHelper;

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
						<h4><a href="<?= TourController::getActionUrl(TourController::ACTION_VIEW, ['id' => $tour->sourceId, 'src' => $tour->source, 'slug' => StringHelper::urlAlias($tour->title) ])?>"><strong><?= $tour->title ?></strong></a></h4>
						<br>
						<?= $tour->sourceId ?><br>
						<?= $tour->imageOld ?><br>
						от <?= $tour->priceMinMax[0] ?> <br>
						<div class="col-md-3 col-xs-12">
							<?php if (null !== $tour->image): ?>
								<?= $tour->image ?>
								<?= Yii::$app->imageCache->thumb($tour->image, '250', ['class' => 'img-rounded']) ?>
							<?php else: ?>
								<?= RemoteImageCache::getImage($tour->imageOld, '250', 'img-rounded') ?>
							<?php endif ?>
						</div>
						<div class="col-md-9 col-xs-12">
							<?= $tour->description ?>
							<?= dosamigos\gallery\Gallery::widget([
									'items' => $tour->additionalImages
								]
							); ?>
							<br>
							<?php foreach ($tour->wayPoints as $wayPoint): ?>
								<?php if ($wayPoint->daysCount > 0 && null !== $wayPoint->country && null !== $wayPoint->countryFlagImage): ?>
									<a href="javascript:" class="way-point-tag" data-value="country_<?= $wayPoint->country ?>"><img width="30" alt="<?= $wayPoint->country ?>" src="<?= $wayPoint->countryFlagImage ?>"></a> <a href="javascript:" class="way-point-tag" data-value="country_<?= $wayPoint->country ?>"><?= $wayPoint->country ?></a>, <a
											href="javascript:" class="way-point-tag" data-value="<?= $wayPoint->cityId ?>"><?=
										$wayPoint->cityName
										?></a><br>
								<?php endif ?>
							<?php endforeach ?>
						</div>
					</div>
				</div>
			</div>
		<?php endforeach ?>
	</div>
<?php
$this->registerJs('$(this).searchTourPlugin();');
$this->registerJs('$(this).commonPlugin();');
?>