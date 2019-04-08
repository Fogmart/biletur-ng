<?php
namespace app\migrations;

use common\components\OracleMigration;
use yii\db\Schema;

/**
 * Class m190402_054542_add_table_place
 */
class m190402_054542_add_table_place extends OracleMigration {
	private $_tableName = 'place';

	public function safeUp() {
		$this->createTable($this->_tableName, [
			'id'                => $this->createPk(),
			'old_id'            => Schema::TYPE_STRING . ' NOT NULL',
			'id1c'              => Schema::TYPE_STRING . ' DEFAULT NULL',
			'aura_id'           => Schema::TYPE_INTEGER . ' DEFAULT NULL',
			'list_aura_id'      => Schema::TYPE_STRING . ' DEFAULT NULL',
			'sale_place'        => Schema::TYPE_STRING . ' DEFAULT NULL',
			'name'              => Schema::TYPE_STRING . ' DEFAULT NULL',
			'filial_id'         => Schema::TYPE_INTEGER . ' NOT NULL',
			'town_id'           => Schema::TYPE_INTEGER . ' NOT NULL',
			'town_code'         => Schema::TYPE_STRING . ' NOT NULL',
			'loc_id'            => Schema::TYPE_INTEGER . ' DEFAULT NULL',
			'loc_name'          => Schema::TYPE_STRING . ' DEFAULT NULL',
			'office_manager_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
			'email'             => Schema::TYPE_STRING . ' DEFAULT NULL',
			'rang'              => Schema::TYPE_INTEGER . ' DEFAULT NULL',
			'is_filial_hq'      => Schema::TYPE_BOOLEAN . ' DEFAULT ON NULL 0',
			'is_hide_frm_web'   => Schema::TYPE_BOOLEAN . ' DEFAULT ON NULL 0',
			'is_published'      => Schema::TYPE_BOOLEAN . ' DEFAULT ON NULL 0',
			'is_eorder'         => Schema::TYPE_BOOLEAN . ' DEFAULT ON NULL 0',
			'is_sale_iata'      => Schema::TYPE_BOOLEAN . ' DEFAULT ON NULL 0',
			'is_sale_avia'      => Schema::TYPE_BOOLEAN . ' DEFAULT ON NULL 0',
			'is_sale_rail_road' => Schema::TYPE_BOOLEAN . ' DEFAULT ON NULL 0',
			'is_sale_tour'      => Schema::TYPE_BOOLEAN . ' DEFAULT ON NULL 0',
			'is_sale_paper'     => Schema::TYPE_BOOLEAN . ' DEFAULT ON NULL 0',
			'is_sale_sputnik'   => Schema::TYPE_BOOLEAN . ' DEFAULT ON NULL 0',
			'address'           => Schema::TYPE_STRING . ' DEFAULT NULL',
			'description_url'   => Schema::TYPE_STRING . ' DEFAULT NULL',
			'credit_cards'      => Schema::TYPE_STRING . ' DEFAULT NULL',
			'placement'         => Schema::TYPE_STRING . ' DEFAULT NULL',
			'route_info'        => Schema::TYPE_STRING . ' DEFAULT NULL',
			'bp_qty'            => Schema::TYPE_INTEGER . ' DEFAULT NULL',
			'ndsirp_qty'        => Schema::TYPE_INTEGER . ' DEFAULT NULL',
			'tkp_code'          => Schema::TYPE_STRING . ' DEFAULT NULL',
			'rrsup_org_id'      => Schema::TYPE_STRING . ' DEFAULT NULL',
			'nn_validator'      => Schema::TYPE_STRING . ' DEFAULT NULL',
			'su_validator'      => Schema::TYPE_STRING . ' DEFAULT NULL',
			'pcc'               => Schema::TYPE_STRING . ' DEFAULT NULL',
			'amadeus_office_id' => Schema::TYPE_STRING . ' DEFAULT NULL',
			'insert_stamp'      => Schema::TYPE_DATETIME . ' NOT NULL',
			'update_stamp'      => Schema::TYPE_DATETIME . ' NOT NULL',
		]);

		$this->createIndex(null, $this->_tableName, 'old_id', true);
		$this->createIndex(null, $this->_tableName, 'filial_id');
		$this->createIndex(null, $this->_tableName, 'aura_id');
		$this->createIndex(null, $this->_tableName, 'loc_id');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable($this->_tableName);
	}
}
