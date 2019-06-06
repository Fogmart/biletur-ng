<?php

namespace app\migrations;

use common\components\OracleMigration;
use yii\db\Schema;

class m190531_054543_add_table_request_log extends OracleMigration {

	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('log_request', [
			'id'           => $this->createPk(),
			'hostname'     => $this->string()->notNull(),
			'url'          => $this->integer()->defaultValue(0),
			'user_ip'      => $this->string(),
			'type'         => $this->integer(1)->defaultValue(0),
			'insert_stamp' => Schema::TYPE_DATETIME . ' NOT NULL',
		]);

		$this->createIndex(null, 'log_request', 'url');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('log_request');
	}
}
