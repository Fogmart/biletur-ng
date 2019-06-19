<?php

namespace modules\banner\migrations;

use common\components\OracleMigration;
use yii\db\Schema;

/**
 * Class m190402_054543_add_table_message
 */
class m190618_054543_add_table_banner extends OracleMigration {
	private $_tableName = 'banner';

	public function safeUp() {
		$this->createTable($this->_tableName, [
			'id'           => $this->createPk(),
			'title'        => Schema::TYPE_STRING . ' NOT NULL',
			'url'          => Schema::TYPE_STRING . ' NOT NULL',
			'image'        => Schema::TYPE_STRING . ' DEFAULT NULL',
			'zone'         => Schema::TYPE_INTEGER . ' NOT NULL',
			'beg_date'     => Schema::TYPE_DATETIME . ' NOT NULL',
			'end_date'     => Schema::TYPE_DATETIME . ' NOT NULL',
			'click_count'  => Schema::TYPE_INTEGER . ' DEFAULT 0',
			'show_count'   => Schema::TYPE_INTEGER . ' DEFAULT 0',
			'utm'          => Schema::TYPE_STRING . ' DEFAULT NULL',
			'insert_stamp' => Schema::TYPE_DATETIME . ' NOT NULL',
			'update_stamp' => Schema::TYPE_DATETIME . ' NOT NULL',
		]);

		$this->createIndex(null, $this->_tableName, ['zone', 'beg_date', 'end_date']);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable($this->_tableName);
	}
}
