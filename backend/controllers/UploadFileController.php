<?php
namespace backend\controllers;

use common\components\tour\CommonTour;
use common\models\ObjectFile;
use common\models\oracle\scheme\t3\RefItems;
use common\modules\banner\models\Banner;
use Yii;
use yii\caching\TagDependency;
use yii\filters\AccessControl;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\helpers\Url;

/**
 * Контроллер загрузки файлов
 *
 * @author Исаков Владислав <newsperparser@gmail.com>
 */
class UploadFileController extends BackendController {
	const ACTION_INDEX = 'index';
	const ACTION_DELETE = 'delete';
	const ACTION_DELETE_FROM_CATALOG = 'delete-from-catalog';
	const ACTION_DELETE_FROM_CATEGORY = 'delete-from-category';
	const ACTION_GET_FILE = 'get-file';

	/**
	 * {@inheritdoc}
	 */
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::class,
				'rules' => [
					[
						'actions' => [
							'index',
							static::ACTION_INDEX,
							static::ACTION_GET_FILE,
							static::ACTION_DELETE,
						],
						'allow'   => true,
						'roles'   => ['@'],
					],
				],
			],
		];
	}

	/**
	 * Загрузка
	 *
	 * @param string $objectName Модель к которой подгружаем файл
	 * @param int    $objectId   Идентификатор данных к которым привязываем файл
	 *
	 * @return array
	 *
	 * @throws \yii\web\MethodNotAllowedHttpException
	 * @throws \yii\web\NotFoundHttpException
	 *
	 * @author Исаков Владислав <newsperparser@gmail.com>
	 */
	public function actionIndex($objectName, $objectId) {
		Yii::$app->response->format = Response::FORMAT_JSON;

		if (false === Yii::$app->request->isAjax) {
			throw new MethodNotAllowedHttpException();
		}

		$response = [];

		switch ($objectName) {
			case Banner::class:
				$object = Banner::findOne($objectId);
				if (null === $object) {
					throw new NotFoundHttpException('Баннер не найден.');
				}
				break;
			case CommonTour::class:
				$object = RefItems::findOne([RefItems::ATTR_ID => $objectId]);
				if (null === $object) {
					throw new NotFoundHttpException('Тур не найден.');
				}
				break;
			default:
				$response[] = ['error' => 'Объект не зарегистрирован для загрузки файлов.'];
				break;
		}

		if (count($response) > 0) {
			return $response;
		}

		$objectFile = new ObjectFile();

		$objectFile->object = $objectName;
		$objectFile->object_id = $objectId;
		$objectFile->file = UploadedFile::getInstance($object, $object::ATTR_FILE);

		if (null === $objectFile->file) {
			$response[] = ['error' => 'Произошла ошибка. Обратитись к системным администраторам.'];

			return $response;
		}
		$objectFile->filename = $objectFile->file->name;

		if (false === $objectFile->validate($object::ATTR_FILE)) {
			$response[] = ['error' => $objectFile->getFirstErrors()];

			return $response;
		}

		if (!$objectFile->save(false)) {
			$response[] = ['error' => 'Невозможно сохранить данные о файле. Обратитись к системным администраторам.'];

			return $response;
		}

		$file = $objectFile->file;

		$objectFile = ObjectFile::findOne([
			ObjectFile::ATTR_OBJECT_ID => $objectFile->object_id,
			ObjectFile::ATTR_OBJECT    => $objectFile->object,
			ObjectFile::ATTR_MESSAGE   => $objectFile->filename,
		]);

		TagDependency::invalidate(Yii::$app->cache, [$objectFile->object]);

		$objectFile->file = $file;

		if (false === $objectFile->file->saveAs($objectFile->getDir() . '/' . $objectFile->filename, true)) {
			$response[] = ['error' => 'Невозможно сохранить файл. Обратитись к системным администраторам.'];
			$objectFile->delete();

			return $response;
		}

		$response['files'][] = [
			'name'       => $objectFile->file->name,
			'type'       => $objectFile->file->type,
			'size'       => $objectFile->file->size,
			'url'        => static::getActionUrl(static::ACTION_GET_FILE, ['id' => $objectFile->id]),
			'deleteUrl'  => static::getActionUrl(static::ACTION_DELETE, ['id' => $objectFile->id]),
			'deleteType' => 'POST'
		];

		return $response;
	}

	/**
	 * Удаление файла
	 *
	 * @param int $id
	 *
	 * @return \yii\web\Response
	 *
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 * @throws \yii\web\NotFoundHttpException
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionDelete($id) {
		Yii::$app->response->format = Response::FORMAT_JSON;

		$objectFile = ObjectFile::findOne($id);

		if (null === $objectFile) {
			throw new NotFoundHttpException();
		}

		$objectFile->delete();

		return $this->redirect(Url::previous());
	}

	/**
	 * Скачивание файла по идентификатору
	 *
	 * @param int $id
	 *
	 * @return yii\web\Response
	 *
	 * @throws \yii\web\NotFoundHttpException
	 *
	 * @author Исаков Владислав <newsperparser@gmail.com>
	 */
	public function actionGetFile($id) {
		$objectFile = ObjectFile::findOne($id);

		if (null === $objectFile) {
			throw new NotFoundHttpException();
		}

		$path = $objectFile->getFullPath();

		return Yii::$app->response->sendFile($path, $objectFile->filename);
	}
}