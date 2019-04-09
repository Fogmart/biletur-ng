<?php
namespace common\modules\api\interfaces;

/**
 * Интерфейс для обьектов Отеля из разных источников
 *
 *
 * @author  Исаков Владислав
 */
interface IHotel {

	/**
	 * Название отеля
	 *
	 * @return string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function getTitle();

	/**
	 * Главное изображение
	 *
	 * @return string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function getMainImage();

	/**
	 * Массив изображений
	 *
	 * @return string[]
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function getImages();

	/**
	 * Адрес
	 *
	 * @return string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function getAddress();

	/**
	 * Вариант размещения для отображения
	 *
	 * @return mixed
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function getFirstRate();

}