<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 */

class OracleMigration extends \yii\db\Migration {

	/**
	 * Autoincrement pk for Oracle
	 *
	 * @return string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function createPk() {
		return 'number(10) GENERATED AS IDENTITY';
	}
}