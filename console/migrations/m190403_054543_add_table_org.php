<?php

namespace app\migrations;

use common\components\OracleMigration;
use yii\db\Schema;

/**
 * Class m190402_054543_add_table_filial
 */
class m190403_054543_add_table_org extends OracleMigration {
	private $_tableName = 'org';

	public function safeUp() {
		$this->createTable($this->_tableName, [
			'id'                    => $this->createPk(),
			'old_id'                => Schema::TYPE_STRING . ' NOT NULL',
			'pre_id'                => Schema::TYPE_INTEGER . ' DEFAULT NULL',
			'parent_id'             => Schema::TYPE_STRING . ' DEFAULT NULL',
			'id_1c'                 => Schema::TYPE_STRING . ' DEFAULT NULL',
			'aura_id'               => Schema::TYPE_INTEGER . ' DEFAULT NULL',
			'name'                  => Schema::TYPE_STRING . ' DEFAULT NULL',
			'org_type'              => Schema::TYPE_STRING . ' DEFAULT NULL',
			'currency_convert_type' => Schema::TYPE_SMALLINT . ' DEFAULT NULL',
			'org_form'              => Schema::TYPE_STRING . ' DEFAULT NULL',
			'inn'                   => Schema::TYPE_STRING . ' DEFAULT NULL',
			'kpp'                   => Schema::TYPE_STRING . ' DEFAULT NULL',
			'okonh'                 => Schema::TYPE_STRING . ' DEFAULT NULL',
			'okpo'                  => Schema::TYPE_STRING . ' DEFAULT NULL',
			'grp'                   => Schema::TYPE_STRING . ' DEFAULT NULL',
			'phone'                 => Schema::TYPE_STRING . ' DEFAULT NULL',
			'fax'                   => Schema::TYPE_STRING . ' DEFAULT NULL',
			'email'                 => Schema::TYPE_STRING . ' DEFAULT NULL',
			'is_supplier'           => Schema::TYPE_BOOLEAN . ' DEFAULT ON NULL 0',
			'is_pay_all'            => Schema::TYPE_BOOLEAN . ' DEFAULT ON NULL 0',
			'is_demo'               => Schema::TYPE_BOOLEAN . ' DEFAULT ON NULL 0',
			'service_fil_id'        => Schema::TYPE_STRING . ' DEFAULT NULL',
			'end_date'              => Schema::TYPE_DATETIME . ' DEFAULT NULL',
			'website'               => Schema::TYPE_STRING . ' DEFAULT NULL',
			'insert_stamp'          => Schema::TYPE_DATETIME . ' NOT NULL',
			'update_stamp'          => Schema::TYPE_DATETIME . ' NOT NULL',
		]);

		$this->createIndex(null, $this->_tableName, 'old_id', true);
		$this->createIndex(null, $this->_tableName, 'parent_id');
		$this->createIndex(null, $this->_tableName, 'inn');
		$this->createIndex(null, $this->_tableName, 'kpp');
		$this->createIndex(null, $this->_tableName, 'aura_id');

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable($this->_tableName);
	}
}
