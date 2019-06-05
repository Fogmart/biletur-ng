<?php

namespace modules\profile\migrations;

use common\components\OracleMigration;
use yii\db\Schema;

/**
 * Class m190605_054543_add_table_order
 */
class m190605_054543_add_table_order extends OracleMigration {
	private $_tableName = 'profile';

	public function safeUp() {
		$this->createTable($this->_tableName, [
			'id'           => $this->createPk(),
			'user_id'      => $this->integer()->notNull(),

			'insert_stamp' => Schema::TYPE_DATETIME . ' NOT NULL',
			'update_stamp' => Schema::TYPE_DATETIME . ' NOT NULL',
		]);

		$this->createIndex(null, $this->_tableName, ['user_id']);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable($this->_tableName);
	}
}
