<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 *
 * @var \common\components\tour\CommonTour $tour
 */

use common\base\helpers\Dump;
use common\components\RemoteImageCache;
use common\components\tour\CommonTour;
use sem\helpers\Html;

?>

	<h3><?= $tour->title ?></h3>
<div class="clearfix">
	<?php if (null !== $tour->image): ?>
		<?= Html::img(Yii::$app->imageresize->getUrl($tour->image, 195, 195), ['class' => 'img-rounded f-left']); ?>
	<?php else: ?>
		<?= RemoteImageCache::getImage($tour->imageOld, '195', 'img-rounded f-left', false, true, ($tour->source == CommonTour::SOURCE_BILETUR)) ?>
	<?php endif ?>
	<?= $tour->description ?>
</div>
<?php
Dump::dDie($tour);
?>