<?php
/**
 * @author isakov.v
 *
 * @var $cities common\models\scheme\sns\Towns[]
 *
 */
?>

<div class="modal fade" id="city-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Выбор города</h4>
            </div>
            <div class="modal-body">
                <div class="row">
					<?php foreach ($cities as $city): ?>
                        <div class="col-xs-6">
                            <h4><a href="<?= \yii\helpers\Url::to(
									['site/change-city', 'cityId' => $city->ID]
								) ?>"><?= $city->RNAME ?></a></h4>
                        </div>
					<?php endforeach ?>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- /.modal -->
