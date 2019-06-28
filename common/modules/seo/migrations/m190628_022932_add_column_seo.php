<?php

namespace modules\seo\migrations;

use common\components\OracleMigration;
use yii\db\Schema;

class m190628_022932_add_column_seo extends OracleMigration {
	private $_tableName = 'seo';

	public function up() {
		$this->addColumn($this->_tableName, 'object', Schema::TYPE_STRING . ' DEFAULT NULL');
		$this->addColumn($this->_tableName, 'object_id', Schema::TYPE_INTEGER . ' DEFAULT NULL');

		$this->createIndex(null, $this->_tableName, ['object', 'object_id'], true);
	}

	public function down() {
		$this->dropColumn($this->_tableName, 'object');
		$this->dropColumn($this->_tableName, 'object_id');
	}
}
