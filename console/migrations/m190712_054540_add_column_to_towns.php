<?php

namespace app\migrations;

use common\components\OracleMigration;
use yii\db\Schema;

/**
 * Class m190402_054540_add_table_towns
 */
class m190712_054540_add_column_to_towns extends OracleMigration {
	private $_tableName = 'town';

	public function safeUp() {
		$this->addColumn($this->_tableName, 'id_geobase', Schema::TYPE_INTEGER . ' DEFAULT NULL');
		$this->createIndex(null, $this->_tableName, 'id_geobase', true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn($this->_tableName . 'id_geobase');
	}
}
