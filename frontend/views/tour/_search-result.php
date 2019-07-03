<?php
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
		<?php
		\common\base\helpers\Dump::d($tours);
		?>
	</div>
<?php foreach ($tours as $tour): ?>

<?php endforeach ?>
<?php
$this->registerJs('$(this).searchTourPlugin();');
?>