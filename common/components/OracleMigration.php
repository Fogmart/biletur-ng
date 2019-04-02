<?php

namespace common\components;

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

	/**
	 * Создание индекса
	 *
	 * @param null         $name
	 * @param string       $tableName
	 * @param array|string $field
	 * @param bool         $uq
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function createIndex($name, $tableName, $field, $uq = false) {
		if (is_array($field)) {
			$alias = implode('-', $field);
		}
		else {
			$alias = $field;
		}

		if (null === $name) {
			if ($uq) {
				$name = 'uq-' . strtolower($tableName) . '-' . $alias;
			}
			else {
				$name = 'ix-' . strtolower($tableName) . '-' . $alias;
			}
		}

		$name = substr($name, 0, 30);

		parent::createIndex($name, $tableName, $field, $uq);
	}
}