<?php

namespace common\interfaces;
/**
 * @author isakov.v
 *
 * Интерфейс необходим для синхронизации данных в моделях оракла дсп и оракла сайта.
 */
interface ILinkedModels {

	/**
	 * Поле с ид в оракле дсп
	 *
	 * @return string
	 *
	 * @author Исаков Владислав
	 */
	public static function getOldIdField();

	/**
	 * Связка таблиц. Массив ключ-значение вида: ['SITE_TABLE' => 'DSP_TABLE']
	 *
	 * @return array
	 *
	 * @author Исаков Владислав
	 */
	public static function getLinkedModel();

	/**
	 * Поле таблицы-приемника для сравнения изменений
	 *
	 * @return string
	 *
	 * @author Исаков Владислав
	 */
	public static function getInternalInvalidateField();

	/**
	 * Поле таблицы-поставщика для сравнения изменений
	 *
	 * @return string
	 *
	 * @author Исаков Владислав
	 */
	public static function getOuterInvalidateField();

	/**
	 * Связка полей. Массив ключ-значение вида: ['SITE_TABLE_FIELD_NAME' => 'DSP_TABLE_FIELD_NAME']
	 * Используется в синхронизации через array_flip() т.к. так удобнее
	 *
	 * @return array
	 *
	 * @author Исаков Владислав
	 */
	public static function getLinkedFields();


	/**
	 * Получение сконвертированного значения поля.
	 * Если конвертировать нечего то должен содержать 'return $data';
	 * Иначе добавляем конвертации для нужных полей по параметру $fieldName
	 *
	 * @param string $fieldName
	 * @param mixed  $data
	 *
	 * @return mixed
	 *
	 * @author Исаков Владислав
	 */
	public static function getConvertedField($fieldName, $data);
}