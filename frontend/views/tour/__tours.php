<?php

use common\base\helpers\LString;
use common\base\helpers\StringHelper;
use common\components\RemoteImageCache;
use common\components\tour\CommonTour;
use frontend\controllers\TourController;

/**
 * @author Исаков Владислав <visakov@biletur.ru>
 * @var \common\components\tour\CommonTour[] $tours
 */
?>
<?php foreach ($tours as $tour): ?>
	<?php
	// \common\base\helpers\Dump::d($tour->sourceTourData);
	?>
	<div class="row tour-block" style="display: none;">
		<div class="col-xs-12">
			<div class="tour-item">
				<h4><a href="<?= TourController::getActionUrl(TourController::ACTION_VIEW, ['id' => $tour->sourceId, 'src' => $tour->source, 'slug' => StringHelper::urlAlias($tour->title)]) ?>"><strong><?= $tour->title ?></strong></a></h4>
				<br>
				<b>от <?= LString::formatMoney($tour->priceMinMax[0]) ?> </b><br>
				<b>дней <?= $tour->daysCount ?> </b><br>
				<div class="col-md-3 col-xs-12">
					<?php if (null !== $tour->image): ?>
						<?php //Yii::$app->imageCache->thumb($tour->image, '250', ['class' => 'img-rounded']) ?>

					<?php else: ?>
						<?= RemoteImageCache::getImage($tour->imageOld, '250', 'img-rounded', false, true, ($tour->sourceId == CommonTour::SOURCE_BILETUR)) ?>
					<?php endif ?>
				</div>
				<div class="col-md-9 col-xs-12">
					<?= $tour->description ?>
					<br>
					<?php foreach ($tour->wayPoints as $country => $wayPoints): ?>
						<b><a href="javascript:" class="way-point-tag" data-value="country_<?= $country ?>"><img width="30" alt="<?= $country ?>" src="<?= $wayPoints[0]->countryFlagImage ?>"></a> <a href="javascript:" class="way-point-tag" data-value="country_<?= $country ?>"><?= $country ?></a>:</b>
						<?php foreach ($wayPoints as $wayPoint): ?>
							<a href="javascript:" class="way-point-tag" data-value="<?= $wayPoint->cityId ?>"><?= $wayPoint->cityName ?></a>
						<?php endforeach ?>
						<br>
					<?php endforeach ?>
				</div>
			</div>
		</div>
	</div>
<?php endforeach ?>
<?php
$this->registerJs('$(this).searchTourPlugin();');
?>
