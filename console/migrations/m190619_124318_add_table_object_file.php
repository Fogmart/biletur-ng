<?php
namespace app\migrations;

use common\components\OracleMigration;
use yii\db\mysql\Schema;

class m190619_124318_add_table_object_file extends OracleMigration {
	private $_tableName = 'object_file';

	public function safeUp() {
		$this->createTable($this->_tableName, [
				'id'                 => $this->createPk(),
				'object'             => Schema::TYPE_STRING . '(800) NOT NULL',
				'object_id'          => Schema::TYPE_INTEGER . ' NOT NULL',
				'filename'           => Schema::TYPE_STRING . '(600) NOT NULL',
				'create_stamp'       => Schema::TYPE_DATETIME . ' NOT NULL',
			]
		);

		$this->createIndex(null, $this->_tableName, ['object', 'object_id']);
	}

	public function safeDown() {
		$this->dropTable($this->_tableName);
	}
}
