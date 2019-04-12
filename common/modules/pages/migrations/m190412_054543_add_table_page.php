<?php

namespace modules\pages\migrations;

use common\components\OracleMigration;
use yii\db\Schema;

/**
 * Class m190402_054543_add_table_message
 */
class m190412_054543_add_table_page extends OracleMigration {
	private $_tableName = 'page';

	public function safeUp() {
		$this->createTable($this->_tableName, [
			'id'              => $this->createPk(),
			'title'           => Schema::TYPE_STRING . ' NOT NULL',
			'seo_title'       => Schema::TYPE_STRING . ' DEFAULT NULL',
			'seo_description' => Schema::TYPE_STRING . ' DEFAULT NULL',
			'seo_keywords'    => Schema::TYPE_STRING . ' DEFAULT NULL',
			'slug'            => Schema::TYPE_STRING . ' NOT NULL',
			'html'            => Schema::TYPE_TEXT . ' NOT NULL',
			'is_published'    => Schema::TYPE_BOOLEAN . '  DEFAULT ON NULL 0',
			'insert_stamp'    => Schema::TYPE_DATETIME . ' NOT NULL',
			'update_stamp'    => Schema::TYPE_DATETIME . ' NOT NULL',
		]);

		$this->createIndex(null, $this->_tableName, ['slug']);
		$this->createIndex(null, $this->_tableName, ['slug', 'is_published']);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable($this->_tableName);
	}
}
