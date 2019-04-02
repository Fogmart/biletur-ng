<?php

namespace common\components;

use apaoww\oci8\Oci8DbConnection;
use PDO;
use sfedosimov\oci8pdo\Oci8PDO_Connection;

/**
 * @author isakov.v
 *
 * Переопределяем коннекшн к ораклу чтобы модифицировать формат даты для каждой сессии,
 * иначе в поле модели данные из полей DATE попадают без времени
 */
class SiteConnection extends Oci8PDO_Connection {
	public function initConnection() {
		parent::initConnection();
		$this->pdo->exec("ALTER SESSION SET NLS_TIME_FORMAT = 'HH24:MI:SS' NLS_DATE_FORMAT = 'YYYY-MM-DD HH24:MI:SS' NLS_TIMESTAMP_FORMAT = 'YYYY-MM-DD HH24:MI:SS' NLS_TIMESTAMP_TZ_FORMAT = 'YYYY-MM-DD HH24:MI:SS TZH:TZM'");
		$this->pdo->setAttribute(PDO::ATTR_CASE,PDO::CASE_LOWER);
	}
}

