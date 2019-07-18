<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@web', dirname(dirname(__DIR__)) . '/frontend/web');
Yii::setAlias('@images', dirname(dirname(__DIR__)) . '/frontend/web/images');
Yii::setAlias('@uploads', dirname(dirname(__DIR__)) . '/frontend/web/images/uploads');
Yii::setAlias('@remoteImageCache', dirname(dirname(__DIR__)) . '/frontend/web/images/cache');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@temp', dirname(dirname(__DIR__)) . '/temp');
Yii::setAlias('@visa', dirname(dirname(__DIR__)) . '/visa'); //Обязательно при введении отдельной папки для поддоменов
Yii::setAlias('@tourTransData', dirname(dirname(__DIR__)) . '/frontend/web/temp/tourtrans'); //Куда качаем файлы Туртранса для обновления их каталога туров
