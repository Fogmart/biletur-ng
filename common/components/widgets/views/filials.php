<?php
/**
 * @author isakov.v
 *
 * @var $filials array()
 *
 */
?>
<div class="widget-block-header">Офисы продаж</div>
<div class="widget-block">
	<?php foreach ($filials as $region => $regionFilials): ?>
        <div class="widget-block-content-header"><span><?= $region ?></span></div>
        <div class="widget-block-content">
			<?php foreach ($regionFilials as $filial): ?>
                <a href="<?= \yii\helpers\Url::to(['filials/city', 'id' => $filial['CITYID']]) ?>"><?= $filial['CITYNAME'] ?></a>
			<?php endforeach ?>
        </div>
	<?php endforeach ?>
</div>
