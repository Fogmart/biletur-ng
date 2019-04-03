<?php
namespace app\migrations;

use common\components\OracleMigration;
use yii\db\Schema;

/**
 * Class m190402_054541_add_table_countries
 */
class m190402_054541_add_table_countries extends OracleMigration {
	private $_tableName = 'country';

	public function safeUp() {
		$this->createTable($this->_tableName, [
			'id'                => $this->createPk(),
			'old_id'            => Schema::TYPE_STRING . ' NOT NULL',
			'name'              => Schema::TYPE_STRING . ' DEFAULT NULL',
			'r_name'            => Schema::TYPE_STRING . ' NOT NULL',
			'e_name'            => Schema::TYPE_STRING . ' NOT NULL',
			'code'              => Schema::TYPE_STRING . ' NOT NULL',
			'aura_id'           => Schema::TYPE_INTEGER . ' DEFAULT NULL',
			'shwinguide'        => Schema::TYPE_SMALLINT . ' DEFAULT ON NULL 0',
			'yandex_weather_id' => Schema::TYPE_STRING . ' DEFAULT NULL',
			'flag_image'        => Schema::TYPE_STRING . ' DEFAULT NULL',
			'insert_stamp'      => Schema::TYPE_DATETIME . ' NOT NULL',
			'update_stamp'      => Schema::TYPE_DATETIME . ' NOT NULL',
		]);

		$this->createIndex(null, $this->_tableName, 'old_id', true);
		$this->createIndex(null, $this->_tableName, 'aura_id');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable($this->_tableName);
	}
}
