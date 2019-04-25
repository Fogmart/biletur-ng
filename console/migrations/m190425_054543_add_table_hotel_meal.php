<?php

namespace app\migrations;

use common\components\OracleMigration;
use yii\db\Schema;

class m190425_054543_add_table_hotel_meal extends OracleMigration {

	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('api_ostrovok_meal', [
			'id'               => $this->createPk(),
			'slug'             => $this->string()->notNull(),
			'title'            => $this->string()->notNull(),
			'common_filter_id' => $this->integer()->notNull(),
			'insert_stamp'     => Schema::TYPE_DATETIME . ' NOT NULL',
			'update_stamp'     => Schema::TYPE_DATETIME . ' NOT NULL',
		]);

		$this->createIndex(null, 'api_ostrovok_meal', 'slug');
		$this->createIndex(null, 'api_ostrovok_meal', 'common_filter_id');

		$this->createTable('common_hotel_meal', [
			'id'           => $this->createPk(),
			'title'        => $this->string()->notNull(),
			'insert_stamp' => Schema::TYPE_DATETIME . ' NOT NULL',
			'update_stamp' => Schema::TYPE_DATETIME . ' NOT NULL',
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('api_ostrovok_meal');
		$this->dropTable('common_hotel_meal');
	}
}
