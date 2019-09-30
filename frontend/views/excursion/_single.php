<?php

use common\base\helpers\DateHelper;
use kartik\rating\StarRating;

/**
 * @author Исаков Владислав <visakov@biletur.ru>
 *
 * @var \common\components\excursion\CommonExcursion $excursion
 *
 */
?>
<div class="excursion-block">
	<div class="col-xs-5" style="margin-left: -20px;">
		<a target="_blank" href="<?= $excursion->url ?>" rel="nofollow">
			<img class="img img-responsive" src="<?= $excursion->image ?>">
		</a>
	</div>
	<div class="name"><a target="_blank" href="<?= $excursion->url ?>" rel="nofollow"><?= $excursion->name ?></a></div>
	<div class="duration visible-lg visible-md visible-sm"><i class="glyphicon glyphicon-time"> </i> <?= $excursion->duration ?> ч.</div>
	<div class="rating">
		<div class="guide">
			<a target="_blank" href="<?= $excursion->guide->url ?>" rel="nofollow"><img src="<?= $excursion->guide->avatarMedium ?>" class="img-circle" width="50"> <b><?= $excursion->guide->firstName ?></b></a>
		</div>
		<?= StarRating::widget([
			'name'          => 'rating',
			'value'         => $excursion->rating,
			'pluginOptions' => [
				'displayOnly' => true,
				'showCaption' => false,
				'size'        => 'xs',
			]
		]); ?>
		<span class="review-count"><?= $excursion->reviewCount ?> отзывов</span>
	</div>

	<div class="annotation"><?= $excursion->annotation ?></div>
	<div class="schedule-block">
		<?php
		$i = 0;
		?>
		<?php foreach ($excursion->schedule as $date => $times): ?>
			<div class="schedule"><?= DateHelper::intlFormat($date, DateHelper::INTL_FORMAT_DATE_RU_EX) ?>, <span class="time"><?= $times->timeStart ?></span></div>
			<?php
			$i++;
			if ($i === 2) {
				break;
			}
			?>
		<?php endforeach ?>
	</div>
	<div class="price">
		<?php if (null !== $excursion->price->discount): ?>
			<span class="old-price"><?= $excursion->price->currency ?> <?= $excursion->price->discount->oldPrice ?></span>
		<?php endif ?>
		<span class="current-price"><?= $excursion->price->currency ?> <?= $excursion->price->value ?></span> <span class="current-price-unit"><?= $excursion->price->unitString ?></span>
	</div>
</div>