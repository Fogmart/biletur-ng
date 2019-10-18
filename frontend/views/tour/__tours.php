<?php

use common\base\helpers\LString;
use common\base\helpers\StringHelper;
use common\components\RemoteImageCache;
use common\components\tour\CommonTour;
use frontend\controllers\TourController;
use sem\helpers\Html;

/**
 * @author Исаков Владислав <visakov@biletur.ru>
 * @var \common\components\tour\CommonTour[] $tours
 */
?>
<div class="row tour-block" style="display: none;">
	<?php foreach ($tours as $tour): ?>
		<div class="col-xs-6">
			<div class="tour-item">
				<div class="cover">
					<?php if (null !== $tour->image): ?>
						<?= Html::img(Yii::$app->imageresize->getUrl($tour->image, 235, 235), ['class' => 'img-rounded']); ?>
					<?php else: ?>
						<?= RemoteImageCache::getImage($tour->imageOld, '235', 'img-rounded', false, true, ($tour->source == CommonTour::SOURCE_BILETUR)) ?>
					<?php endif ?>
				</div>
				<div class="days">от <?= LString::formatMoney($tour->priceMinMax[0]) ?> <b>дней <?= $tour->daysCount ?> </b></div>
				<h4><a href="<?= TourController::getActionUrl(TourController::ACTION_VIEW, ['id' => $tour->sourceId, 'src' => $tour->source, 'slug' => StringHelper::urlAlias($tour->title)]) ?>"><strong><?= $tour->title ?></strong></a></h4>
				<div class="tags">
					<?php foreach ($tour->wayPoints as $country => $wayPoints): ?>
						<b><a href="javascript:" class="way-point-tag" data-value="country_<?= $country ?>"><img width="30" alt="<?= $country ?>" src="<?= $wayPoints[0]->countryFlagImage ?>"></a> <a href="javascript:" class="way-point-tag" data-value="country_<?= $country ?>"><?= $country ?></a>:</b>
						<?php foreach ($wayPoints as $wayPoint): ?>
							<a href="javascript:" class="way-point-tag" data-value="<?= $wayPoint->cityId ?>"><?= $wayPoint->cityName ?></a>
						<?php endforeach ?>
					<?php endforeach ?>
				</div>
			</div>
		</div>

	<?php endforeach ?>
</div>
<?php $this->registerJs('$(this).searchTourPlugin();'); ?>
<?php $this->registerJs('$(this).commonPlugin();'); ?>
