<?php

namespace modules\profile\migrations;

use common\components\OracleMigration;
use yii\db\Schema;

/**
 * Class m190604_054543_add_table_profile
 */
class m190604_054543_add_table_profile extends OracleMigration {
	private $_tableName = 'profile';

	public function safeUp() {
		$this->createTable($this->_tableName, [
			'id'           => $this->createPk(),
			'user_id'      => $this->integer()->notNull(),
			'f_name'       => $this->string(),
			's_name'       => $this->string(),
			'l_name'       => $this->string(),
			'email'        => $this->string(),
			'phone'        => $this->string(11),
			'dob'          => $this->string(),
			'city_id'      => $this->string(),
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
