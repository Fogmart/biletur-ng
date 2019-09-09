<?php

namespace console\controllers;

use common\components\CsvImporter;
use common\components\TelCapacity;
use common\models\GeobaseCity;
use common\models\Town;
use Yii;
use yii\console\Controller;
use yii\db\Connection;
use yii\di\Instance;
use yii\mongodb\Query;
use yii\validators\EmailValidator;

/**
 * Разные утилиты
 *
 * @package app\commands
 *
 * @author  Исаков Владислав <isakov.vi@dns-shop.ru>
 */
class UtilController extends Controller {

	public $db = 'dbDsp';
	public $scheme = 'sns';
	public $createTb;
	public $fieldUpper = true;

	public function options($actionID) {
		return ['createTb', 'db', 'scheme', 'fieldUpper'];
	}

	public function optionAliases() {
		return ['tb' => 'createTb', 'db' => 'db', 'scheme' => 'scheme', 'fieldUpper' => 'fieldUpper'];
	}

	/**
	 * Создние модели на основе БД
	 *
	 * @author Исаков Владислав <isakov.vi@dns-shop.ru>
	 */
	public function actionCreateModel() {
		/** @var \yii\db\Connection $db */
		$db = Yii::$app->get($this->db);

		$db = Instance::ensure($db, Connection::class);
		$tableSchema = $db->schema->getTableSchema($this->createTb);

		$classFile = '<?php ' . PHP_EOL . PHP_EOL;
		if ($this->scheme === 'sns' || $this->scheme === 'arr' || $this->scheme === 't3' || $this->scheme === 'tour') {
			$classFile .= 'namespace common\models\oracle\scheme\\' . $this->scheme . ';' . PHP_EOL . PHP_EOL;
		}
		else {
			$classFile .= 'namespace common\models;' . PHP_EOL . PHP_EOL;
		}

		$classFile .= 'use yii\db\ActiveRecord;' . PHP_EOL . PHP_EOL;

		$classFile .= '/**' . PHP_EOL . PHP_EOL;
		$classFile .= '* Поля таблицы:' . PHP_EOL;

		foreach ($tableSchema->columns as $columnName => $columnParam) {
			$fieldName = strtolower($columnName);
			if ($this->fieldUpper) {
				$fieldName = strtoupper($columnName);
			}
			$classFile .= '* @property ' . $columnParam->phpType . ' $' . $fieldName . PHP_EOL;
		}

		$classFile .= '*/' . PHP_EOL . PHP_EOL;

		$classFile .= 'class ' . ucfirst(strtolower($this->createTb)) . ' extends ActiveRecord {' . PHP_EOL . PHP_EOL;

		foreach ($tableSchema->columns as $columnName => $columnParam) {
			$fieldName = $columnName;
			if ($this->fieldUpper) {
				$fieldName = strtoupper($columnName);
			}

			$classFile .= "	const ATTR_" . strtoupper($columnName) . " = '" . $fieldName . "';" . PHP_EOL;
		}

		$classFile .= PHP_EOL . PHP_EOL;

		$classFile .= '	public static function tableName() {' . PHP_EOL;
		if ($this->fieldUpper) {
			$classFile .= "		return '{{" . strtoupper($this->scheme) . "." . strtoupper($this->createTb) . "}}';" . PHP_EOL;
		}
		else {
			$classFile .= "		return ' . $this->createTb . ';" . PHP_EOL;
		}

		$classFile .= '	}' . PHP_EOL . PHP_EOL;

		$classFile .= '	public function attributeLabels() {' . PHP_EOL;
		$classFile .= '		return [' . PHP_EOL;
		foreach ($tableSchema->columns as $columnName => $columnParam) {
			$classFile .= "			static::ATTR_" . strtoupper($columnName) . " => '" . $columnName . "'," . PHP_EOL;
		}

		$classFile .= '		];' . PHP_EOL;
		$classFile .= '	}' . PHP_EOL;

		$classFile .= '}' . PHP_EOL;

		$path = 'common' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR;

		if ($this->scheme === 'sns' || $this->scheme === 'arr' || $this->scheme === 't3' || $this->scheme === 'tour') {
			$path .= 'oracle' . DIRECTORY_SEPARATOR . 'scheme' . DIRECTORY_SEPARATOR . $this->scheme . DIRECTORY_SEPARATOR;
		}

		file_put_contents($path . '_' . ucfirst(strtolower($this->createTb)) . '.php', $classFile);
	}

	/**
	 * Привязка справочника городов к базе геолокации
	 *
	 * @throws \yii\db\Exception
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionLinkGeoTown() {
		/** @var GeobaseCity[] $geoCities */
		$geoCities = GeobaseCity::find()->all();
		foreach ($geoCities as $geoCity) {
			/** @var Town $town */
			$town = Town::findOne([Town::ATTR_NAME => $geoCity->name]);
			if (null === $town) {
				continue;
			}
			$town->id_geobase = $geoCity->id;
			$town->save();
		}
	}

	/**
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionCsvConv() {
		$csv = Yii::getAlias('@tourTransData') . DIRECTORY_SEPARATOR . '660.csv';

		$importer = new CsvImporter($csv, false, '|');
		$data = $importer->get();

		$validRecords = [];
		$notValidRecords = [];

		foreach ($data as $row) {
			$email = $row[6];
			$validator = new EmailValidator();
			$companyName = explode('/', $row[1]);

			if ($validator->validate($email)) {
				$validRecords[] = [$row[6], $companyName[0]];
			}
			else {
				$notValidRecords[] = [$companyName[0]];
			}
		}

		$fp = fopen(Yii::getAlias('@tourTransData') . DIRECTORY_SEPARATOR . '660_valid.csv', 'w');
		foreach ($validRecords as $fields) {
			fputcsv($fp, $fields);
		}
		fclose($fp);

		$fp = fopen(Yii::getAlias('@tourTransData') . DIRECTORY_SEPARATOR . '660_not_valid.csv', 'w');
		foreach ($notValidRecords as $fields) {
			fputcsv($fp, $fields);
		}
		fclose($fp);
	}

	/**
	 * @throws \yii\mongodb\Exception
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionLoadTelBase() {
		$csvFiles[] = Yii::getAlias('@tourTransData') . DIRECTORY_SEPARATOR . 'ABC-3xx.csv';
		$csvFiles[] = Yii::getAlias('@tourTransData') . DIRECTORY_SEPARATOR . 'ABC-4xx.csv';
		$csvFiles[] = Yii::getAlias('@tourTransData') . DIRECTORY_SEPARATOR . 'ABC-8xx.csv';
		$csvFiles[] = Yii::getAlias('@tourTransData') . DIRECTORY_SEPARATOR . 'DEF-9xx.csv';
		$capacity = [];

		foreach ($csvFiles as $csvFile) {
			$importer = new CsvImporter($csvFile, false, ';');
			$data = $importer->get();
			foreach ($data as $row) {
				$telCapacity = new TelCapacity(
					[
						TelCapacity::ATTR_CODE            => (int)$row[0],
						TelCapacity::ATTR_BEG_NUMBER      => (int)$row[1],
						TelCapacity::ATTR_END_NUMBER      => (int)$row[2],
						TelCapacity::ATTR_OPERATOR        => 'X',
						TelCapacity::ATTR_ORIGIN_OPERATOR => $row[4],
						TelCapacity::ATTR_REGION          => $row[5],
					]
				);

				if (array_key_exists($row[4], TelCapacity::OP_NAMES)) {
					$telCapacity->operator = TelCapacity::OP_NAMES[$row[4]];
				}

				$capacity[] = $telCapacity;
			}
		}

		$collection = Yii::$app->mongodb->getCollection(TelCapacity::COLLECTION_CAPACITY);
		if ($collection->count() > 0) {
			$collection->drop();
		}

		Yii::$app->mongodb->createCommand()->batchInsert(TelCapacity::COLLECTION_CAPACITY, $capacity);
		$collection->createIndex([TelCapacity::ATTR_CODE, TelCapacity::ATTR_BEG_NUMBER, TelCapacity::ATTR_END_NUMBER]);
	}

	/**
	 * Конвертирование
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionConvertPrim() {
		$csvFiles[] = Yii::getAlias('@tourTransData') . DIRECTORY_SEPARATOR . '1.csv';
		$csvFiles[] = Yii::getAlias('@tourTransData') . DIRECTORY_SEPARATOR . '2.csv';
		$csvFiles[] = Yii::getAlias('@tourTransData') . DIRECTORY_SEPARATOR . '3.csv';
		$csvFiles[] = Yii::getAlias('@tourTransData') . DIRECTORY_SEPARATOR . '4.csv';
		$csvFiles[] = Yii::getAlias('@tourTransData') . DIRECTORY_SEPARATOR . '5.csv';
		$csvFiles[] = Yii::getAlias('@tourTransData') . DIRECTORY_SEPARATOR . '6.csv';
		$csvFiles[] = Yii::getAlias('@tourTransData') . DIRECTORY_SEPARATOR . '7.csv';
		$csvFiles[] = Yii::getAlias('@tourTransData') . DIRECTORY_SEPARATOR . '8.csv';

		$fNum = 1;
		foreach ($csvFiles as $csvFile) {
			$newData = [];
			$importer = new CsvImporter($csvFile, false, ';');
			$data = $importer->get();
			$i = 1;
			$operatorRegion = [];
			foreach ($data as $row) {
				static::showStatus($i, count($data));
				$fullNumber = $row[0];
				if ('7' === substr($fullNumber, 0, 1)) {
					$fullNumber = '8' . substr($fullNumber, 1, 10);
				}

				$operCode = substr($fullNumber, 1, 3);
				$number = substr($fullNumber, 4, 7);

				$query = new Query();
				$query->select([])->from(TelCapacity::COLLECTION_CAPACITY);
				$query->andWhere([TelCapacity::ATTR_CODE => (int)$operCode]);
				$query->andWhere(['<=', TelCapacity::ATTR_BEG_NUMBER, (int)$number]);
				$query->andWhere(['>=', TelCapacity::ATTR_END_NUMBER, (int)$number]);

				$capacity = $query->one();

				$operator = 'Не найден';
				$operatorCapacityCode = 'Не найден';
				$convertedPhone = 'Не найден';

				if (false !== $capacity) {
					$operator = $capacity[TelCapacity::ATTR_ORIGIN_OPERATOR];
					$operatorCapacityCode = $capacity[TelCapacity::ATTR_OPERATOR];
					$convertedPhone = $operatorCapacityCode . $fullNumber;
				}

				$newData[] = array_merge($row, [
					$operatorCapacityCode,
					$operator,
					$convertedPhone,
				]);
				$key = $operator . ' - ' . $capacity[TelCapacity::ATTR_REGION];

				if (!array_key_exists($key, $operatorRegion)) {
					$operatorRegion[$key] = 0;
				}

				$operatorRegion[$key]++;
				$i++;
			}

			$fp = fopen(Yii::getAlias('@tourTransData') . DIRECTORY_SEPARATOR . $fNum . '_converted.csv', 'w');
			foreach ($newData as $fields) {
				fputcsv($fp, $fields, ';', ' ');
			}
			foreach ($operatorRegion as $key => $value) {
				fputcsv($fp, [$key, $value], ';', ' ');
			}

			fclose($fp);
			$fNum++;
		}
	}

	/**
	 * show a status bar in the console
	 *
	 * <code>
	 * for($x=1;$x<=100;$x++){
	 *
	 *     show_status($x, 100);
	 *
	 *     usleep(100000);
	 *
	 * }
	 * </code>
	 *
	 * @param int $done  how many items are completed
	 * @param int $total how many items are to be done total
	 * @param int $size  optional size of the status bar
	 *
	 * @return  void
	 *
	 */
	public static function showStatus($done, $total, $size = 30) {

		static $start_time;

		// if we go over our bound, just ignore it
		if ($done > $total) {
			return;
		}

		if (empty($start_time)) {
			$start_time = time();
		}

		$now = time();

		$perc = (double)($done / $total);

		$bar = floor($perc * $size);

		$status_bar = "\r[";
		$status_bar .= str_repeat("=", $bar);
		if ($bar < $size) {
			$status_bar .= ">";
			$status_bar .= str_repeat(" ", $size - $bar);
		}
		else {
			$status_bar .= "=";
		}

		$disp = number_format($perc * 100, 0);

		$status_bar .= "] $disp%  $done/$total";

		$rate = ($now - $start_time) / $done;
		$left = $total - $done;
		$eta = round($rate * $left, 2);

		$elapsed = $now - $start_time;

		$status_bar .= " remaining: " . number_format($eta) . " sec.  elapsed: " . number_format($elapsed) . " sec.";

		echo "$status_bar  ";

		flush();

		// when done, send a newline
		if ($done == $total) {
			echo "\n";
		}
	}
}
