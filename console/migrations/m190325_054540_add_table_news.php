<?php
namespace app\migrations;

use yii\db\Schema;

/**
 * Class m190325_054540_add_table_news
 */
class m190325_054540_add_table_news extends \common\components\OracleMigration {
	private $_tableName = 'news';

	public function safeUp() {

		$this->createTable($this->_tableName, [
			'id'           => $this->createPk(),
			'old_id'       => Schema::TYPE_INTEGER . ' NOT NULL',
			'category_id'  => Schema::TYPE_SMALLINT . ' NOT NULL',
			'date'         => Schema::TYPE_DATETIME . ' NOT NULL',
			'title'        => Schema::TYPE_STRING . ' NOT NULL',
			'text'         => Schema::TYPE_TEXT . ' NOT NULL',
			'is_published' => Schema::TYPE_BOOLEAN . ' DEFAULT ON NULL 0',
			'is_hot'       => Schema::TYPE_BOOLEAN . ' DEFAULT ON NULL 0',
			'image'        => Schema::TYPE_STRING,
			'lang'         => Schema::TYPE_STRING,
			'insert_stamp' => Schema::TYPE_DATETIME . ' NOT NULL',
			'update_stamp' => Schema::TYPE_DATETIME . ' NOT NULL',
		]);


		$this->createIndex(null, $this->_tableName, 'old_id', true);
		$this->createIndex(null, $this->_tableName, ['category_id', 'date', 'is_published', 'lang']);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('NEWS');
	}
}
