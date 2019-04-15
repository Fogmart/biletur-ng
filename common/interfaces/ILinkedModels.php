<?php

namespace common\interfaces;
/**
 * @author isakov.v
 *
 * Интерфейс необходим для синхронизации данныз в моделях оракла и постгрес.
 */
interface ILinkedModels {

	/**
	 * Поле с ид в оракле дсп
	 *
	 * @return string
	 *
	 * @author Исаков Владислав <isakov.vi@dns-shop.ru>
	 */
	public static function getOldIdField();

	/**
	 * Связка таблиц
	 *
	 * @return array
	 *
	 * @author Исаков Владислав <isakov.vi@dns-shop.ru>
	 */
	public static function getLinkedModel();

	/**
	 * Поле внутренней таблицы для сравнения изменений
	 *
	 * @return string
	 *
	 * @author Исаков Владислав
	 */
	public static function getInternalInvalidateField();

	/**
	 * Поле внешней таблицы для сравнения изменений
	 *
	 * @return string
	 *
	 * @author Исаков Владислав
	 */
	public static function getOuterInvalidateField();

	/**
	 * Связка полей
	 *
	 * @return array
	 *
	 * @author Исаков Владислав
	 */
	public static function getLinkedFields();


	/**
	 * Получение сконвертированного значения поля
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