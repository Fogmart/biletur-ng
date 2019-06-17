<?php
namespace common\models;


use common\components\SiteModel;
use Yii;
use yii\validators\FileValidator;

/**
 * Файлы объектов
 *
 * @property int    $id
 * @property string $object
 * @property int    $object_id
 * @property string $filename
 * @property string $create_stamp
 *
 */
class ObjectFile extends SiteModel {

	const ATTR_ID = 'id';
	const ATTR_OBJECT = 'object';
	const ATTR_OBJECT_ID = 'object_id';
	const ATTR_MESSAGE = 'filename';
	const ATTR_CREATE_STAMP = 'create_stamp';

	/** @var \yii\web\UploadedFile */
	public $file;
	const ATTR_FILE = 'file';

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[static::ATTR_FILE, FileValidator::class, 'extensions' => 'gif, jpg, jpeg, png']
		];
	}

	/**
	 * Возвращение пути к файлу для скачивания
	 *
	 * @return string
	 *
	 * @author Исаков Владислав
	 */
	public function getFullPath() {
		return $this->getPath() . '/' . $this->filename;
	}

	/**
	 * Возвращение пути к фйлу для отображения
	 *
	 * @return string
	 *
	 * @author Исаков Владислав
	 */
	public function getWebUrl() {
		return '/images/uploads' . DIRECTORY_SEPARATOR . substr($this->create_stamp, 0, 10) . DIRECTORY_SEPARATOR . md5($this->object . $this->object_id) . DIRECTORY_SEPARATOR . $this->filename;
	}

	/**
	 * Генерация пути к файлу
	 *
	 * @return string
	 *
	 * @author Исаков Владислав
	 */
	public function getPath() {
		$objectFile = ObjectFile::findOne($this->id);

		$path = Yii::getAlias('@rootdir/frontend/web/images/uploads') . DIRECTORY_SEPARATOR . substr($objectFile->create_stamp, 0, 10) . DIRECTORY_SEPARATOR . md5($objectFile->object . $objectFile->object_id);

		return $path;
	}

	/**
	 * Создание и возврат папки
	 *
	 * @return string
	 *
	 * @author Исаков Владислав
	 */
	public function getDir() {
		$path = $this->getPath();

		if (!is_dir($path)) {
			mkdir($path, 0777, true);
		}

		return $path;
	}
}