<?php
namespace app\migrations;

use common\components\OracleMigration;
use yii\db\Schema;

/**
 * Class m190402_054543_add_table_filial
 */
class m190402_054543_add_table_filial extends OracleMigration {
	private $_tableName = 'filial';

	public function safeUp() {
		$this->createTable($this->_tableName, [
			'id'           => $this->createPk(),
			'old_id'       => Schema::TYPE_STRING . ' NOT NULL',
			'filial_code'  => Schema::TYPE_STRING . ' DEFAULT NULL',
			'aura_code'    => Schema::TYPE_STRING . ' DEFAULT NULL',
			'name'         => Schema::TYPE_STRING . ' DEFAULT NULL',
			'org_id'       => Schema::TYPE_INTEGER . ' DEFAULT NULL',
			'group'        => Schema::TYPE_STRING . ' DEFAULT NULL',
			'boss_id'      => Schema::TYPE_INTEGER . ' DEFAULT NULL',
			'boss_name'    => Schema::TYPE_STRING . ' DEFAULT NULL',
			'rang'         => Schema::TYPE_INTEGER . ' DEFAULT NULL',
			'region'       => Schema::TYPE_INTEGER . ' DEFAULT NULL',
			'beg_date'     => Schema::TYPE_DATETIME . ' DEFAULT NULL',
			'end_date'     => Schema::TYPE_DATETIME . ' DEFAULT NULL',
			'insert_stamp' => Schema::TYPE_DATETIME . ' NOT NULL',
			'update_stamp' => Schema::TYPE_DATETIME . ' NOT NULL',
		]);

		$this->createIndex(null, $this->_tableName, 'old_id', true);
		$this->createIndex(null, $this->_tableName, 'org_id');
		$this->createIndex(null, $this->_tableName, 'boss_id');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable($this->_tableName);
	}
}
