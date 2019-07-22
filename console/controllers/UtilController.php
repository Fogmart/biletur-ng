<?php

namespace console\controllers;

use common\components\CsvImporter;
use common\models\GeobaseCity;
use common\models\Town;
use Yii;
use yii\console\Controller;
use yii\db\Connection;
use yii\di\Instance;
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

	public function actionHorConv() {
		$csv = Yii::getAlias('@tourTransData') . DIRECTORY_SEPARATOR . 'horeca.csv';

		$importer = new CsvImporter($csv, false, '|');
		$data = $importer->get();
		$validRecords = [];
		$notValidRecords = [];
		foreach ($data as $row) {
			$email = $row[5];
			$validator = new EmailValidator();
			$companyName = explode('/', $row[0]);
			if ($validator->validate($email)) {
				$validRecords[] = [$companyName[0], $row[5]];
			}
			else {
				$notValidRecords[] = [$companyName[0]];
			}
		}

		$fp = fopen(Yii::getAlias('@tourTransData') . DIRECTORY_SEPARATOR . 'horeca_valid.csv', 'w');
		foreach ($validRecords as $fields) {
			fputcsv($fp, $fields);
		}
		fclose($fp);

		$fp = fopen(Yii::getAlias('@tourTransData') . DIRECTORY_SEPARATOR . 'horeca_not_valid.csv', 'w');
		foreach ($notValidRecords as $fields) {
			fputcsv($fp, $fields);
		}
		fclose($fp);
	}
}
