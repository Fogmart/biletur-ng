<?php

namespace common\components;

use apaoww\oci8\Oci8DbConnection;

/**
 * @author isakov.v
 *
 * Переопределяем коннекшн к ораклу чтобы модифицировать формат даты для каждой сессии,
 * иначе в поле модели данные из полей DATE попадают без времени
 */
class DspConnection extends Oci8DbConnection {
	public function initConnection() {
		parent::initConnection();
		$this->pdo->exec("alter session set NLS_DATE_FORMAT='DD-MM-YYYY HH24:MI:SS'");
	}
}