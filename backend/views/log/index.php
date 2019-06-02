<?php
use backend\controllers\LogController;
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 * @var \common\models\LogYii[] $log
 */

?>
<p><a href="<?= LogController::getActionUrl(LogController::ACTION_CLEAR) ?>" class="btn btn-danger btn-sm">Очистить</a></p>
<table class="table table-bordered table-striped log">
	<tr>
		<th>Дата</th>
		<th>Сообщение</th>
		<th>Сайт</th>
		<th>Хост</th>
	</tr>
<?php foreach ($log as $logRecord):?>
	<tr>
		<td><?= $logRecord->log_time ?></td>
		<td class="log-message">
			<p><b><?= $logRecord->category ?></b></p>
			<pre><?= $logRecord->message ?></pre>
		</td>
		<td><?= $logRecord->site_id ?></td>
		<td><?= $logRecord->hostname ?></td>
	</tr>
<?php endforeach ?>
</table>

