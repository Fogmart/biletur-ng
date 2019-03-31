<?php

namespace common\models\oracle\scheme\arr;

use common\models\oracle\scheme\DspBaseModel;
use common\components\helpers\LArray;
use Yii;
use yii\caching\TagDependency;
use yii\db\ActiveQuery;

/**
 * @author isakov.v
 *
 * Модель рейсов из схемы ARR
 *
 * Поля таблицы:
 *
 * @property int    $FL_ID
 * @property int    $CARR_ID
 * @property string $CARR_CODE
 * @property string $CARR_CODE_IATA
 * @property string $FL_NO
 * @property string $BEG_DT
 * @property string $END_DT
 * @property string $DPT_WEEKDAYS
 * @property int    $SHOWINWEB
 * @property int    $APL_TYPE_ID
 * @property string $CRAFT
 * @property string $WHOCRT
 * @property string $WHNCRT
 * @property string $WHOUPD
 * @property string $WHNUPD
 *
 */
class ARRFlights extends DspBaseModel {

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{ARR.FLIGHTS}}';
	}

	/**
	 * Получение рейсов без периода. Использовал нативный запрос
	 * т.к. реализовать это с помощью ORM оказалось весьма затратно для времени и тяжело для психики.
	 * Но оставил наработки по переделке этого на ORM
	 *
	 * @param $depTownId
	 * @param $arvTownId
	 *
	 * @return array
	 *
	 */
	public static function getAllFlights($depTownId, $arvTownId) {
		if (Yii::$app->env->getLanguage() == 'en') {
			$lang = ['name_en', 'lname_lat'];
		}
		else {
			$lang = ['name_ru', 'sname_rus'];
		}

		$tmp_partic_arrival = '';

		if ($arvTownId == '') {
			$tmp_partic_arrival = "fs.fl_seg_no";
			$tmp_having = " having max(decode(f_a_dep.city_id, '" . $depTownId . "', 1)) = 1";
		}
		else {
			$tmp_having = " having max(decode(f_a_dep.city_id, '" . $depTownId
				. "', f_fs.fl_seg_no, null)) <= max(decode(f_a_arv.city_id, '" . $arvTownId
				. "', f_fs.fl_seg_no, null))";
		}

		$sql
			= "select * from (select distinct (select f.chrtid from sns.chrt_blocks f where f.carrcode || ' ' || f.flnum = e.fl_no_iata and f.dptdate between e.beg_dt and e.end_dt and rownum<2 and nvl(f.active,1) =1) chrtid, e.*
								from
								 (select c.* from
									(select b.fl_id,
									   max(b.craft) craft,
									   max(b.fl_no_iata) fl_no_iata,
									   max(b.carr_id) carr_id,
									   to_char(max(b.beg_dt),'YYYYMMDD') beg_dt_sort,
									   to_char(max(b.beg_dt),'DD.MM.YYYY') beg_dt,
									   to_char(max(b.end_dt),'DD.MM.YYYY') end_dt,
									   max(b.per) per,
									   max(b.first_offset) first_offset,
									   max(b.last_offset)-max(b.first_offset) offset,
									   max(b.dpt_weekdays) dpt_weekdays,
									   max(b.city_id) city_id,
									   Initcap(max(b.dep_sname)) dep_sname,
									   Initcap(max(b.arv_sname)) arv_sname,
									   max(b.country_name) country_name,
									   max(b.s) route,
									   max(to_char(b.dep_tm, 'HH24:MI')) dep_tm,
									   max(to_char(b.arv_tm, 'HH24:MI')) arv_tm
								  from (select fl_id, rn, craft, fl_no_iata, carr_id, beg_dt, end_dt, per,
											   min(a.city_id) keep(DENSE_RANK first order by a.lvl desc) OVER(partition by a.fl_id, rn) city_id,
											   min(a.dep_offset) keep(DENSE_RANK first order by a.lvl) OVER(partition by a.fl_id, rn) first_offset,
											   min(a.arv_offset) keep(DENSE_RANK first order by a.lvl desc) OVER(partition by a.fl_id, rn) last_offset,
											   timetbl.get_segment_weekdays(a.dpt_weekdays, min(a.dep_offset) keep(DENSE_RANK first order by a.lvl) OVER(partition by a.fl_id, rn)) dpt_weekdays,
											   min(dep_sname) keep(DENSE_RANK first order by a.lvl) OVER(partition by a.fl_id, rn) dep_sname,
											   min(arv_sname) keep(DENSE_RANK first order by a.lvl desc) OVER(partition by a.fl_id, rn) arv_sname,
											   min(country_name) keep(DENSE_RANK first order by a.lvl desc) OVER(partition by a.fl_id, rn) country_name,
											   a.s,
											   min(a.dep_tm) keep(DENSE_RANK first order by a.lvl) OVER(partition by a.fl_id, rn) dep_tm,
											   min(a.arv_tm) keep(DENSE_RANK first order by a.lvl desc) OVER(partition by a.fl_id, rn) arv_tm
										  from (select nvl(f.carr_code_iata,f.carr_code) || ' ' || f.fl_no fl_no_iata,
													   fs.dep_offset dep_offset,
													   fs.arv_offset arv_offset,
													   level lvl,
													   f.fl_id,
													   rn,
													   f.craft,
													   f.beg_dt,
													   f.end_dt,
													   f.beg_dt || ' &ndash; ' || f.end_dt per,
													   f.dpt_weekdays,
													   initcap(c_arv." . $lang[1] . ") city_id,
													   lpad(sys_connect_by_path(Initcap(c_arv." . $lang[1]
			. "), ' &rarr; '), instr(sys_connect_by_path(Initcap(c_arv." . $lang[1] . "), ' &rarr; '), ' &rarr; ', -1) + 6) s,
													   fs.fl_seg_no,
													   fs.dep_tm,
													   a_dep." . $lang[0] . " dep_sname,
													   fs.arv_tm,
													   a_arv." . $lang[0] . " arv_sname,
													   decode(cn.cou_id, 956428, ' ', ' ('||Initcap(cn." . $lang[1] . ")||')') country_name,
													   f.carr_id
												  from arr.flights_seg fs,
													   arr.airport a_dep,
													   arr.airport a_arv,
													   arr.city c_dep,
													   arr.city c_arv left join arr.country cn on c_arv.cou_id = cn.cou_id,
														(SELECT rownum rn, f_sub.*, first_seg, " . $tmp_partic_arrival . " last_seg from
														   arr.flights f_sub,
														   (SELECT f_f.fl_id,
																   max(decode(f_a_dep.city_id, '" . $depTownId . "', f_fs.fl_seg_no)) first_seg,
																   max(decode(f_a_arv.city_id, 0, '" . $arvTownId . "', f_fs.fl_seg_no)) last_seg
															  FROM arr.flights     f_f,
																   arr.flights_seg f_fs,
																   arr.airport     f_a_dep,
																   arr.airport     f_a_arv
															 where 1 = 1
															   and f_f.fl_id = f_fs.fl_id
															   and f_fs.dep_ap_id = f_a_dep.aura_id and f_fs.arv_ap_id = f_a_arv.aura_id
															   and f_f.showinweb=1 and trunc(f_f.end_dt) >= trunc(sysdate)
															 group by f_f.fl_id
															 " . $tmp_having . ") f_2,
															arr.flights_seg fs
														where f_2.fl_id = fs.fl_id
														  and f_2.fl_id = f_sub.fl_id
														  and fs.fl_seg_no >= f_2.first_seg) f
												 where 1 = 1
												   and f.fl_id = fs.fl_id
												   and fs.fl_seg_no between f.first_seg and f.last_seg
												   and fs.dep_ap_id = a_dep.aura_id
												   and fs.arv_ap_id = a_arv.aura_id
												   and a_dep.city_id = c_dep.city_id
												   and a_arv.city_id = c_arv.city_id
												 start with fs.fl_seg_no = first_seg
												connect by prior f.fl_id = f.fl_id and prior f.rn = f.rn
													   and prior fs.fl_seg_no + 1 = fs.fl_seg_no) a) b
								 group by b.fl_id, rn) c) e
								 order by e.city_id, e.beg_dt_sort, e.dep_tm)";

		$cacheKey = Yii::$app->cache->buildKey([$sql]);
		$rows = Yii::$app->cache->get($cacheKey);
		if (false === $rows) {
			$connection = Yii::$app->get('dbDsp');
			$rows = $connection->createCommand($sql)->queryAll();
			$rows = LArray::group($rows, 'CITY_ID');
			Yii::$app->cache->set($cacheKey, $rows, null, new TagDependency([ARRFlights::class]));
		}

		return $rows;
	}

	/**
	 * Запрос рейсов на дату со смещением даты
	 *
	 * @param $depTownId
	 * @param $arvTownId
	 * @param $date
	 * @param $dateShift
	 *
	 * @return array
	 */
	public static function getFlightsByDate($depTownId, $arvTownId, $date, $dateShift = 7) {
		if (Yii::$app->env->getLanguage() == 'en') {
			$lang = ['name_en', 'lname_lat'];
		}
		else {
			$lang = ['name_ru', 'sname_rus'];
		}
		$sql = "select * from (select distinct f.chrtid, e.* from
								 (select c.*, to_char(d.fl_date, 'DD.MM.YYYY') fl_date, to_char(d.fl_date, 'YYYYMMDD') fl_date_sys from
									(select b.fl_id,
									   max(b.craft) craft,
									   max(b.fl_no_iata) fl_no_iata,
									   max(b.carr_id) carr_id,
									   max(b.per) per,
									   max(b.first_offset) first_offset,
									   max(b.last_offset)-max(b.first_offset) offset,
									   max(b.dpt_weekdays) dpt_weekdays,
									   max(b.city_id) city_id,
									   Initcap(max(b.dep_sname)) dep_sname,
									   Initcap(max(b.arv_sname)) arv_sname,
									   max(b.country_name) country_name,
									   max(b.s) route,
									   max(to_char(b.dep_tm, 'HH24:MI')) dep_tm,
									   max(to_char(b.arv_tm, 'HH24:MI')) arv_tm
								  from (select a.fl_id,
											   a.craft,
											   a.fl_no_iata,
											   a.carr_id,
											   a.per,
											   min(a.city_id) keep(DENSE_RANK first order by a.lvl desc) OVER(partition by a.fl_id) city_id,
											   min(a.dep_offset) keep(DENSE_RANK first order by a.lvl) OVER(partition by a.fl_id) first_offset,
											   min(a.arv_offset) keep(DENSE_RANK first order by a.lvl desc) OVER(partition by a.fl_id) last_offset,
											   timetbl.get_segment_weekdays(a.dpt_weekdays, min(a.dep_offset) keep(DENSE_RANK first order by a.lvl) OVER(partition by a.fl_id)) dpt_weekdays,
											   min(dep_sname) keep(DENSE_RANK first order by a.lvl) OVER(partition by a.fl_id) dep_sname,
											   min(arv_sname) keep(DENSE_RANK first order by a.lvl desc) OVER(partition by a.fl_id) arv_sname,
											   min(country_name) keep(DENSE_RANK first order by a.lvl desc) OVER(partition by a.fl_id) country_name,
											   a.s,
											   min(a.dep_tm) keep(DENSE_RANK first order by a.lvl) OVER(partition by a.fl_id) dep_tm,
											   min(a.arv_tm) keep(DENSE_RANK first order by a.lvl desc) OVER(partition by a.fl_id) arv_tm
										  from (select nvl(f.carr_code_iata,f.carr_code) || ' ' || f.fl_no AS fl_no_iata,
													   fs.dep_offset dep_offset,
													   fs.arv_offset arv_offset,
													   level lvl,
													   f.fl_id,
													   f.craft,
													   f.beg_dt || ' &ndash; ' || f.end_dt per,
													   f.dpt_weekdays,
													   initcap(c_arv." . $lang[1] . ") city_id,
													   lpad(sys_connect_by_path(Initcap(c_arv." . $lang[1]
			. "), ' &rarr; '), instr(sys_connect_by_path(Initcap(c_arv." . $lang[1] . "), ' &rarr; '), ' &rarr; ', -1) + 6) s,
													   fs.fl_seg_no,
													   fs.dep_tm,
													   a_dep." . $lang[0] . " dep_sname,
													   fs.arv_tm,
													   a_arv." . $lang[0] . " arv_sname,
													   decode(cn.cou_id, 956428, ' ', ' ('||Initcap(cn." . $lang[1] . ")||')') country_name,
													   f.carr_id
												  from arr.flights f,
													   arr.flights_seg fs,
													   arr.airport a_dep,
													   arr.airport a_arv,
													   arr.city c_dep,
													   arr.city c_arv left join arr.country cn on c_arv.cou_id = cn.cou_id,
													   (SELECT f_f.fl_id,
															   max(decode(f_a_arv.city_id, " . $arvTownId . ", f_fs.fl_seg_no)) fl_last_seg
														  FROM arr.flights     f_f,
															   arr.flights_seg f_fs,
															   arr.airport     f_a_dep,
															   arr.airport     f_a_arv
														 where 1 = 1
														   and f_f.fl_id = f_fs.fl_id
														   and f_fs.dep_ap_id = f_a_dep.aura_id
														   and f_fs.arv_ap_id = f_a_arv.aura_id
														   and f_f.showinweb=1
														 group by f_f.fl_id
														having max(decode(f_a_dep.city_id, " . $depTownId
			. ", f_fs.fl_seg_no, null))<= max(decode(f_a_arv.city_id, " . $arvTownId . ", f_fs.fl_seg_no, null))) ff
												 where 1 = 1
												   and f.fl_id = fs.fl_id
												   and fs.dep_ap_id = a_dep.aura_id
												   and fs.arv_ap_id = a_arv.aura_id
												   and a_dep.city_id = c_dep.city_id
												   and a_arv.city_id = c_arv.city_id
												   and f.fl_id = ff.fl_id
												 start with a_dep.city_id = " . $depTownId . "
												connect by prior f.fl_id = f.fl_id
													   and prior fs.fl_seg_no + 1 = fs.fl_seg_no
													   and fs.fl_seg_no <= fl_last_seg) a) b
								 group by b.fl_id) c, table(timetbl.get_flights_in_period(c.fl_id,c.dpt_weekdays,c.first_offset,'"
			. $date . "'," . $dateShift . ")) d
								 ) e left join sns.chrt_blocks f ON e.fl_date = f.dptdate and f.carrcode || ' ' || f.flnum = e.fl_no_iata and nvl(f.active,1) =1
								 order by e.fl_date_sys, e.dep_tm)";


		$cacheKey = Yii::$app->cache->buildKey([$sql]);
		$rows = Yii::$app->cache->get($cacheKey);
		if (false === $rows) {
			$connection = Yii::$app->get('dbDsp');
			$rows = $connection->createCommand($sql)->queryAll();
			$rows = LArray::group($rows, 'FL_DATE');
			Yii::$app->cache->set($cacheKey, $rows, null, new TagDependency([ARRFlights::class]));
		}

		return $rows;
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
		return 'WHNUPD';
	}

	/**
	 * Получение сегментов рейса
	 * @return ActiveQuery
	 */
	public function getSegments() {
		return $this->hasMany(ARRFlightsSeg::class, ['FL_ID' => 'FL_ID'])->orderBy('FL_SEG_NO');
	}
}