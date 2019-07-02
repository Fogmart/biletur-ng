<?php

namespace modules\seo\migrations;

use common\components\OracleMigration;
use yii\db\Schema;

class m190416_054543_add_table_seo extends OracleMigration {
	private $_tableName = 'seo';

	public function safeUp() {
		$this->createTable($this->_tableName, [
			'id'              => $this->createPk(),
			'url'             => Schema::TYPE_STRING . ' NOT NULL',
			'seo_title'       => Schema::TYPE_STRING . ' DEFAULT NULL',
			'seo_description' => Schema::TYPE_STRING . ' DEFAULT NULL',
			'seo_keywords'    => Schema::TYPE_STRING . ' DEFAULT NULL',
			'user_id'         => Schema::TYPE_INTEGER . ' NOT NULL',
			'insert_stamp'    => Schema::TYPE_DATETIME . ' NOT NULL',
			'update_stamp'    => Schema::TYPE_DATETIME . ' NOT NULL',
		]);

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable($this->_tableName);
	}
}
