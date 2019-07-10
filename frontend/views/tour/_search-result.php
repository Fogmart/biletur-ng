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
		<?= $this->render('__tours', ['tours' => $tours]) ?>
	</div>
<?php $this->registerJs('$(this).commonPlugin();'); ?>