<?php

namespace common\models\oracle\scheme\sns;

use common\models\oracle\scheme\DspBaseModel;
use common\models\oracle\scheme\sns\queries\QueryAPFlights;
use Yii;
use yii\caching\TagDependency;

/**
 * @author isakov.v
 *
 * Модель расписания рейсов, реазлизует интерфейс InvalidateModels
 *
 * Поля таблицы:
 * @property string $id
 * @property int    $ttid
 * @property string $apcode        Код аэропорта
 * @property string $DEST          Город назначения (рус)
 * @property string $dest_e        Город назначения (англ)
 * @property string $craft         Тип самолета
 * @property string $AIRLINES      Авиакомпания
 * @property string $FLNUM         Номер рейса
 * @property string $PLANDATE      Плановая дата рейса
 * @property string $PLANTIME      Плановое время вылета/прибытия (по расписанию)
 * @property int    $estimated
 * @property int    $dtype         Тип отклонения. 0-без отклонений
 * @property string $FACTDATE
 * @property string $FACTTIME
 * @property string $infodate
 * @property string $infotime
 * @property string $reason
 * @property string $pdspdt
 * @property string $pdspremark
 * @property int    $hide
 * @property string $upddt
 * @property string $editor
 * @property string $pdspupddt
 * @property string $plandt
 * @property string $factdt
 * @property string $ad_type       A/D - Arrival/Departure
 * @property string $whncrt
 */
class DspAPFlights extends DspBaseModel {

	public static function find() {
		return new QueryAPFlights(get_called_class());
	}

	/**
	 * @param string $apCode
	 * @param string $direction
	 *
	 * @return mixed
	 *
	 * @throws \yii\base\InvalidConfigException
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function getFlightsToBoard($apCode, $direction) {
		$sql = "SELECT DEST, AIRLINES, FLNUM ,FACTTIME, PLANDATE, PLANTIME, case
							when DType is null then
								plandate || ' ' || plantime
							else
								nvl(factdate, plandate) || ' ' || nvl(facttime, plantime)
							end as sort,
							decode(FactDate, null, PlanDate, FactDate) as FACTDATE
 				FROM " . DspAPFlights::tableName() . "
				WHERE nvl(hide,0) = 0
				and ad_type = '" . $direction . "'
				and apcode = '" . $apCode . "'
				and
				(

				(to_date(to_char(plandate, 'DD.MM.YYYY') || ' ' || plantime, 'DD.MM.YYYY HH24:MI:SS')
				between (sysdate - 12 / 24) and (sysdate + 12 / 24) )
				or (dtype = 1 and to_date(to_char(FactDate, 'DD.MM.YYYY') || ' ' || FactTime, 'DD.MM.YYYY HH24:MI:SS') >= (sysdate - 12 / 24))
				or (dtype = 2 and plandate <= trunc(sysdate))
				or (dtype = 3 and plandate = trunc(sysdate))
				or (dtype = 4)
				) order by sort";

		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, $apCode, $direction]);
		$rows = Yii::$app->cache->get($cacheKey);
		if (false === $rows) {
			$connection = Yii::$app->get('dbDsp');
			$connection->createCommand("alter session set NLS_DATE_FORMAT='DD-MM-YYYY'")->execute();
			$rows = $connection->createCommand($sql)->queryAll();
			Yii::$app->cache->set($cacheKey, $rows, null, new TagDependency([DspAPFlights::class]));
		}

		return $rows;
	}

	/**
	 * @return string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function tableName() {
		return '{{SNS.AP_FLIGHTS}}';
	}

	/**
	 * Периодичность проверки актуальности кеша
	 * @return int
	 */
	public function getInvalidateTime() {
		return 60 * 60 * 3;
	}

	/**
	 * Поле в таблице по которому проверям актуальность
	 * @return string
	 */
	public function getInvalidateField() {
		return 'UPDDT';
	}

	const FLIGHT_DIRECTION_ARRIVAL = 'A';
	const FLIGHT_DIRECTION_DEPARTURE = 'D';
}
