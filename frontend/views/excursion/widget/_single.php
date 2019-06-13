<?php

use kartik\rating\StarRating;

/**
 * @author Исаков Владислав <visakov@biletur.ru>
 *
 * @var \common\components\excursion\CommonExcursion $excursion
 *
 */
?>
<div class="excursion-block">
    <a target="_blank" href="<?= $excursion->url ?>" rel="nofollow">
        <img class="img img-rounded img-responsive" src="<?= $excursion->image ?>">
    </a>
    <div class="rating">
		<?= StarRating::widget([
			'name'          => 'rating',
			'value'         => $excursion->rating,
			'pluginOptions' => [
				'displayOnly' => true,
				'showCaption' => false,
				'size'        => 'xs',
			]
		]); ?>
    </div>
    <div class="name"><a target="_blank" href="<?= $excursion->url ?>" rel="nofollow"><h4><?= $excursion->name ?></h4></a></div>
    <div class="annotation "><?= $excursion->annotation ?></div>
    <div class="duration visible-lg visible-md visible-sm">Продолжительность: <?= $excursion->duration ?> ч.</div>
    <div class="price visible-lg visible-md visible-sm"><?= $excursion->price ?></div>
</div>