<?php

namespace common\components;

use apaoww\oci8\Oci8DbConnection;

/**
 * @author isakov.v
 *
 * Переопределяем коннекшн к ораклу чтобы модифицировать формат даты для каждой сессии,
 * иначе в поле модели данные из полей DATE попадают без времени
 */
class SiteConnection extends Oci8DbConnection {
	public function initConnection() {
		parent::initConnection();

		$this->pdo->exec("alter session set NLS_DATE_FORMAT='dd-mm-yy hh24:mi:ss'");
	}
}

