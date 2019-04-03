<?php

namespace modules\message\migrations;

use common\components\OracleMigration;
use yii\db\Schema;

/**
 * Class m190402_054543_add_table_message
 */
class m190402_054543_add_table_message extends OracleMigration {
	private $_tableName = 'message';

	public function safeUp() {
		$this->createTable($this->_tableName, [
			'id'           => $this->createPk(),
			'object'       => Schema::TYPE_STRING . ' NOT NULL',
			'object_id'    => Schema::TYPE_STRING . ' NOT NULL',
			'user_id'      => Schema::TYPE_STRING . ' DEFAULT NULL',
			'user_name'    => Schema::TYPE_STRING . ' NOT NULL',
			'message'      => Schema::TYPE_TEXT . ' NOT NULL',
			'insert_stamp' => Schema::TYPE_DATETIME . ' NOT NULL',
			'update_stamp' => Schema::TYPE_DATETIME . ' NOT NULL',
		]);

		$this->createIndex(null, $this->_tableName, ['object', 'object_id']);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable($this->_tableName);
	}
}
