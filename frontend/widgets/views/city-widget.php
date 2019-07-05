<?php

use frontend\controllers\SiteController;

/**
 * @author Исаков Владислав <visakov@biletur.ru>
 * @var array $towns
 */
?>
<div class="modal fade" id="modal-towns">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
							aria-hidden="true">&times;</span></button>
				<h3 class="modal-title">Выбор города</h3>
			</div>
			<div class="modal-body">
				<div class="row">
					<?php foreach ($towns as $letter => $townArray): ?>
						<div class="col-xs-6">
						<span class="alphabet-letter"><?= $letter ?></span><hr>
						<?php foreach ($townArray as $town): ?>
								<h4><a href="<?= SiteController::getActionUrl(SiteController::ACTION_SET_CITY, ['id' => $town->id]) ?>"><?= $town->r_name ?></a></h4>
						<?php endforeach ?>
						</div>
					<?php endforeach ?>
				</div>
			</div>
		</div>
	</div>
</div>