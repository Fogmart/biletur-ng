<?php

namespace common\models\oracle\scheme\sns;

use common\models\oracle\scheme\DspBaseModel;
use common\components\helpers\LArray;
use Yii;
use yii\caching\TagDependency;


/**
 *
 * @author isakov.v
 *
 * Модель таблицы FILIALS
 *
 * Поля таблицы:
 * @property string $ID
 * @property string $FILIAL
 * @property string $AURCODE
 * @property string $NAME
 * @property string $ORGID
 * @property string $GROUPS
 * @property string $BEGDATE
 * @property string $ENDDATE
 * @property string $BOSSID
 * @property string $BOSSNAME
 * @property int    $RANG
 * @property string $WHOCRT
 * @property string $WHNCRT
 * @property string $WHOUPD
 * @property string $WHNUPD
 * @property string $DFLTACNTID
 * @property string $REGION
 * @property string $SRKNSIFLDR
 *
 */
class DspFilials extends DspBaseModel {

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.FILIALS}}';
	}

	/**
	 * Получение филиалов, сгруппированных по региону
	 *
	 * @param string|null $cityId
	 *
	 * @return array|mixed
	 *
	 */
	public static function getAll($cityId = null) {

		$addWhere = "";
		if ($cityId !== null) {
			$addWhere = " AND t.id = '" . $cityId . "' ";
		}

		$sql = "SELECT DISTINCT r.code as RegCode,
					r.name as regname,
					t.id CityID,
					t.ename as ename,
					t.code as citycode,
					t.rname as cityname,
					t.rang CityRang,
					decode(r.code,
						   'ЦР',
						   1,
						   'ВЖ',
						   2,
						   'АУ',
						   3,
						   'ЕИ',
						   4,
						   'КЧ',
						   5,
						   'ХБ',
						   6,
						   'СЯ',
						   7,
						   'ПР',
						   8) ordr,
					fs.url,
					fs.title
					  from places p
					  join plcAddrs pa
						on pa.Placeid = p.Id
					   and pa.Dflt = 1
					  join towns t
						on t.id = pa.cityid
					  join admregs r
						on r.id = t.admregid
					  join filials f
						on f.id = p.filialid
					   and (f.groups = 'АГН'

						   or f.OrgID in (select distinct orgid
											 from orggrplst t
											where orggrpid = '0000000001'))

					  left join filial_site fs
						on fs.cityid = pa.CityID
					 where p.active = '1' " . $addWhere . "
					 order by ordr, r.name, CityRang, t.rname
					";

		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, $cityId]);
		$rows = Yii::$app->cache->get($cacheKey);
		if (false === $rows) {
			$connection = Yii::$app->getDb();
			$rows = $connection->createCommand($sql)->queryAll();
			$rows = LArray::group($rows, 'REGNAME');
			Yii::$app->cache->set($cacheKey, $rows, null, new TagDependency(
					[DspFilials::class, DspPlaces::class, DspPlcAddrs::class, DspAdmRegs::class]
				)
			);
		}

		return $rows;
	}

	/**
	 * Периодичность проверки актуальности кеша
	 * @return int
	 */
	public function getInvalidateTime() {
		return 60 * 60 * 24;
	}

	/**
	 * Поле в таблице по которому проверям актуальность
	 * @return string
	 */
	public function getInvalidateField() {
		return 'WHNUPD';
	}
}