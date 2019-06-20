<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@uploads', dirname(dirname(__DIR__)) . '/frontend/web/images/uploads');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@temp', dirname(dirname(__DIR__)) . '/temp');
Yii::setAlias('@visa', dirname(dirname(__DIR__)) . '/visa'); //Обязательно при введении отдельной папки для поддобменов
