<?php
/**
 * @var \yii\web\View                        $this
 * @var \common\modules\banner\models\Banner $banner
 *
 */
?>
<?php if (null !== $banner): ?>
    <a href="<?= $banner->url ?><?= (!empty($banner->utm) ? '?' . $banner->utm : '') ?>" target="_blank"><img alt="" src="<?= $banner->image ?>"></a>
<?php endif ?>