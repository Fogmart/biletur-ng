<?php

namespace modules\message\migrations;

use common\components\OracleMigration;
use yii\db\Schema;

/**
 * Class m190604_054543_add_table_profile
 */
class m190604_054543_add_table_profile extends OracleMigration {
	private $_tableName = 'message';

	public function safeUp() {
		$this->createTable($this->_tableName, [
			'id'      => $this->createPk(),
			'user_id' => $this->integer()->unsigned()->notNull(),
			'f_name'  => $this->string()->defaultValue(null),
			's_name'  => $this->string()->defaultValue(null),
			'l_name'  => $this->string()->defaultValue(null),
			'email'   => $this->string()->defaultValue(null),
			'phone'   => $this->string()->defaultValue(null),
			'dob'     => $this->string()->defaultValue(null),
			'city_id' => $this->string()->defaultValue(null),
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
