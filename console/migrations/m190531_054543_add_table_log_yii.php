<?php

namespace app\migrations;

use common\components\OracleMigration;
use yii\db\Schema;

class m190531_054543_add_table_log_yii extends OracleMigration {

	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('log_yii', [
			'id'       => $this->createPk(),
			'level'    => $this->string()->notNull(),
			'category' => $this->string()->notNull(),
			'prefix'   => $this->string()->notNull(),
			'message'  => $this->text()->notNull(),
			'hostname' => $this->string()->notNull(),
			'site_id'  => $this->integer()->defaultValue(0),
			'log_time' => Schema::TYPE_DATETIME . ' NOT NULL',
		]);

		$this->createIndex(null, 'log_yii', 'site_id');
		$this->createIndex(null, 'log_yii', 'category');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('log_yii');
	}
}
