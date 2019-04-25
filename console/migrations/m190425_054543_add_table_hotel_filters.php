<?php

namespace app\migrations;

use common\components\OracleMigration;
use yii\db\Schema;

class m190425_054543_add_table_hotel_filters extends OracleMigration {

	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('api_ostrovok_serp_filters', [
			'id'               => $this->createPk(),
			'lang'             => $this->string()->notNull(),
			'slug'             => $this->string()->notNull(),
			'sort_order'       => $this->integer()->notNull(),
			'title'            => $this->string()->notNull(),
			'uid'              => $this->integer()->notNull(),
			'common_filter_id' => $this->integer()->notNull(),
			'insert_stamp'     => Schema::TYPE_DATETIME . ' NOT NULL',
			'update_stamp'     => Schema::TYPE_DATETIME . ' NOT NULL',
		]);

		$this->createIndex(null, 'api_ostrovok_serp_filters', 'slug');
		$this->createIndex(null, 'api_ostrovok_serp_filters', 'common_filter_id');
		$this->createIndex(null, 'api_ostrovok_serp_filters', 'uid');

		$this->createTable('common_hotel_serp_filters', [
			'id'           => $this->createPk(),
			'sort_order'   => $this->integer()->notNull(),
			'title'        => $this->string()->notNull(),
			'insert_stamp' => Schema::TYPE_DATETIME . ' NOT NULL',
			'update_stamp' => Schema::TYPE_DATETIME . ' NOT NULL',
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('api_ostrovok_serp_filters');
		$this->dropTable('common_hotel_serp_filters');
	}
}
