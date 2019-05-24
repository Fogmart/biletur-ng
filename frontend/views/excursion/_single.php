<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 *
 * @var \common\components\excursion\CommonExcursion $excursion
 *
 */
?>
<div class="excursion-block">
    <img class="img img-rounded img-responsive" src="<?= $excursion->image ?>">
    <div class="name"><h4><?= $excursion->name ?></h4></div>
    <div class="annotation"><?= $excursion->annotation ?></div>
    <div class="duration"><?= $excursion->duration ?> часа</div>
    <div class="price"><?= $excursion->price ?></div>
    <div class="rating"><?= $excursion->rating ?></div>
</div>