<?php
namespace app\migrations;

use common\components\OracleMigration;
use yii\db\Schema;

/**
 * Class m190402_054540_add_table_towns
 */
class m190402_054540_add_table_towns extends OracleMigration {
	private $_tableName = 'town';

	public function safeUp() {
		$this->createTable($this->_tableName, [
			'id'                => $this->createPk(),
			'old_id'            => Schema::TYPE_STRING . ' NOT NULL',
			'name'              => Schema::TYPE_STRING . ' DEFAULT NULL',
			'r_name'            => Schema::TYPE_STRING . ' DEFAULT NULL',
			'e_name'            => Schema::TYPE_STRING . ' DEFAULT NULL',
			'code'              => Schema::TYPE_STRING . ' DEFAULT NULL',
			'country_id'        => Schema::TYPE_INTEGER . ' NOT NULL',
			'country_code'      => Schema::TYPE_STRING . ' DEFAULT NULL',
			'adm_reg_id'        => Schema::TYPE_STRING . ' DEFAULT NULL',
			'adm_code'          => Schema::TYPE_STRING . ' DEFAULT NULL',
			'iata_code'         => Schema::TYPE_STRING . ' DEFAULT NULL',
			'ikao_code'         => Schema::TYPE_STRING . ' DEFAULT NULL',
			'phone_code'        => Schema::TYPE_STRING . ' DEFAULT NULL',
			'latitude'          => Schema::TYPE_STRING . ' DEFAULT NULL',
			'longitude'         => Schema::TYPE_STRING . ' DEFAULT NULL',
			'aura_id'           => Schema::TYPE_INTEGER . ' DEFAULT NULL',
			'shwinguide'        => Schema::TYPE_SMALLINT . ' DEFAULT ON NULL 0',
			'yandex_weather_id' => Schema::TYPE_STRING . ' DEFAULT NULL',
			'gmt_shift'         => Schema::TYPE_INTEGER . ' DEFAULT NULL',
			'rang'              => Schema::TYPE_INTEGER . ' DEFAULT NULL',
			'insert_stamp'      => Schema::TYPE_DATETIME . ' NOT NULL',
			'update_stamp'      => Schema::TYPE_DATETIME . ' NOT NULL',
		]);

		$this->createIndex(null, $this->_tableName, 'old_id', true);
		$this->createIndex(null, $this->_tableName, 'country_id');
		$this->createIndex(null, $this->_tableName, 'adm_reg_id');
		$this->createIndex(null, $this->_tableName, 'aura_id', true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable($this->_tableName);
	}
}
