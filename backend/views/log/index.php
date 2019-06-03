<?php

use backend\controllers\LogController;
use common\models\LogYii;

/**
 * @author Исаков Владислав <visakov@biletur.ru>
 * @var \common\models\LogYii[] $log
 */

?>
<p><a href="<?= LogController::getActionUrl(LogController::ACTION_CLEAR) ?>" class="btn btn-danger btn-sm">Очистить</a></p>
<table class="table table-bordered table-striped log">
    <tr>
        <th>#</th>
        <th>Сообщение</th>
    </tr>
	<?php foreach ($log as $logRecord): ?>
        <tr>
            <td>
                <p><?= $logRecord->log_time ?></p>
                <p><?= LogYii::SITE_NAMES[$logRecord->site_id] ?></p>
                <p><?= $logRecord->hostname ?></p>
            </td>
            <td class="log-message">
                <p><b><?= $logRecord->category ?></b></p>
                <pre><?= $logRecord->message ?></pre>
            </td>
        </tr>
	<?php endforeach ?>
</table>

