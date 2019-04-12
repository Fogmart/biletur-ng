<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m190327_232217_add_table_api_ostrovok_amenities
 */
class m190327_232217_add_table_api_ostrovok_amenities extends Migration {

	private $_tableName = 'api_ostrovok_amenities';

	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable($this->_tableName, [
			'id'             => Schema::TYPE_PK,
			'hotel_id'       => Schema::TYPE_INTEGER . ' NOT NULL',
			'group_name'     => Schema::TYPE_STRING . ' NOT NULL',
			'group_slug'     => Schema::TYPE_STRING . ' NOT NULL',
			'json_amenities' => Schema::TYPE_JSON . ' NOT NULL',
		]);

		$this->createIndex('ix-hotel-id', $this->_tableName, 'hotel_id');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable($this->_tableName);
	}
}
