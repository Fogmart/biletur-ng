<?php

namespace common\components\helpers;

use yii\base\Exception;

/**
 * Class LArray
 * Класс для работы с массивами
 *
 * @author Zalatov A.
 */
class LArray {
	/**
	 * Группировка данных по указанному полю
	 * На выходе получаем массив, где в качестве ключей выступает указаный атрибут, а в качестве значений - массив с моделями
	 *
	 * @author Zalatov A.
	 *
	 * @param array  $data               Данные, которые надо сгрупировать
	 * @param string $group_by_attribute Название атрибута
	 * @param string $assoc_key_column   Название столбца, откуда взять значения для ключа массива
	 *
	 * @return array
	 */
	public static function group($data, $group_by_attribute, $assoc_key_column = null) {
		$result = [];

		if (!is_array($data))
			return $data;
		if (count($data) == 0)
			return $result;

		foreach ($data as $item) {
			// -- Определяем идентификатор текущего элемента
			if (is_array($item)) {// Если это массив
				$group_id = $item[$group_by_attribute];
			}
			else {// Если это объект
				$group_id = $item->$group_by_attribute;
			}
			// -- -- -- --

			if (!isset($result[$group_id]))
				$result[$group_id] = [];// Если такой группы ещё нет, создаём

			if ($assoc_key_column === null) {
				$result[$group_id][] = $item;// Добавляем модель в группу
			}
			else {
				// -- Определяем идентификатор текущего элемента
				if (is_array($item)) {// Если это массив
					$assoc_key = $item[$assoc_key_column];
				}
				else {// Если это объект
					$assoc_key = $item->$assoc_key_column;
				}
				// -- -- -- --

				$result[$group_id][$assoc_key] = $item;// Добавляем модель в группу
			}
		}

		return $result;
	}

	/**
	 * Извлечение списка значений указанного атрибута из массива моделей.
	 * Удобно использовать, чтобы, например, извлечь только идентификаторы категорий товаров.
	 *
	 * @author Zalatov A.
	 *
	 * @param array  $items          Массив с данными
	 * @param string $attribute_name Атрибут, значения которого необходимо извлечь
	 * @param bool   $uniqueOnly     Флаг, если в выходном массиве нужны только уникальные значения
	 *
	 * @return array
	 * @throws Exception Вызывает исключение, если элемент не является массивом или объектом
	 */
	public static function extract($items, $attribute_name, $uniqueOnly = true) {
		$result = [];

		foreach ($items as $item) {
			// -- Определяем идентификатор текущего элемента
			if (is_array($item)) {// Если это массив
				$result[] = $item[$attribute_name];
			}
			else if (is_object($item)) {// Если это объект
				$result[] = $item->$attribute_name;
			}
			else {
				throw new Exception("{$item} is not an array/object");
			}
			// -- -- -- --
		}

		if ($uniqueOnly) {
			$result = array_unique($result);// Удаляем дубликаты значений
		}

		return $result;
	}

	/**
	 * Извлечение значений указанного атрибута из массива массивов
	 * Например, чтобы получить список идентификаторов из 10-20-30 моделей
	 * Или, ещё как вариант, получить список городов N пользователей
	 *
	 * @author Zalatov A.
	 *
	 * @param array  $models         Массив с моделями
	 * @param string $attribute_name Название атрибута
	 *
	 * @return array
	 */
	public static function extractAttributeValues($models, $attribute_name) {
		return array_keys(self::numkey2assoc($models, $attribute_name));
	}

	/**
	 * Конвертация численного массива в ассоциативный (например, чтобы ключи были те же, что и идентификаторы категорий).
	 *
	 * @author zalatov.a
	 *
	 * @param array  $items    Массив с данными
	 * @param string $assocKey Название атрибута, значение которого взять в качестве ключа массива.
	 *
	 * @return array
	 */
	public static function numkey2assoc($items, $assocKey = 'primaryKey') {
		if (!is_array($items)) {
			return $items;
		}

		if (count($items) === 0) {
			return [];
		}

		$result = [];

		foreach ($items as $item) {
			// -- Определяем идентификатор элемента
			if ($item === null) {
				$itemId = null;
			}
			else if (is_array($item)) {// Если это массив
				$itemId = $item[$assocKey];
			}
			else {// Если это объект
				$path = explode('.', $assocKey);// Если задана зависимость, например, category.parent.id
				$itemId = $item;
				foreach ($path as $pathNode) {
					$itemId = $itemId->$pathNode;
				}
			}
			// -- -- -- --

			$result[$itemId] = $item;
		}

		return $result;
	}

	/**
	 * Построение линейного списка исходя из родительской зависимости
	 * Проще говоря, сначала строится многомерный массив (дерево), а затем этот массив приводится к одномерному с указанием уровня, на котором находится элемент
	 *
	 * @author Zalatov A.
	 *
	 * @param array  $data        Массив с данными
	 * @param string $primary_key Название атрибута, содержащего идентификатор модели
	 * @param string $parent_key  Название атрибута, содержащего идентификатор родителя
	 * @param int    $parent_id   Идентификатор родительского элемента
	 *
	 * @return array
	 */
	public static function linear($data, $primary_key = 'primaryKey', $parent_key = 'parent_id', $parent_id = 0) {
		$tree_relations = self::tree_relations($data, $primary_key, $parent_key);// Сначала получаем дерево

		$result = self::linear_helper($tree_relations, $primary_key, $parent_key, $parent_id);

		return $result;
	}

	/**
	 * Построение дерева на основе указанного ключа
	 * На выходе получаем многомерный массив
	 *
	 * @author Zalatov A.
	 *
	 * @param array  $data        Массив с данными
	 * @param string $primary_key Название атрибута, содержащего идентификатор модели
	 * @param string $parent_key  Название атрибута, содержащего идентификатор родителя
	 *
	 * @return array
	 */
	public static function tree_relations($data, $primary_key = 'primaryKey', $parent_key = 'parent_id') {
		if (!is_array($data))
			return $data;
		if (count($data) == 0)
			return $data;

		$result = [];

		$queue_parent_ids = [];// Если у элемента указан родитель, то заносим его в этот массив, чтобы потом проверить, есть ли родительский элемент

		foreach ($data as $item) {
			// -- Определяем идентификатор текущего и родительского элемента
			if (is_array($item)) {// Если это массив
				$item_id = $item[$primary_key];
				$parent_id = $item[$parent_key];
			}
			else {// Если это объект
				$item_id = $item->$primary_key;
				$parent_id = $item->$parent_key;
			}
			// -- -- -- --

			// -- Удаляем из проверочного массива этот элемент, а потом посмотрим, сколько потерялось родительских элементов
			if (isset($queue_parent_ids[$item_id]))
				unset($queue_parent_ids[$item_id]);
			// -- -- -- --

			if (!isset($result[$parent_id])) {
				$result[$parent_id] = [];
				$queue_parent_ids[$parent_id] = null;
			}

			$result[$parent_id][$item_id] = $item;
		}

		// -- Если есть потерянные элементы (ДЛЯ ОТЛАДКИ)
		/*
				if (count($queue_parent_ids) > 0) foreach ($queue_parent_ids as $id => $not_used) {
					Dump::d($queue_parent_ids);die;
				}
		*/

		// -- -- -- --

		return $result;
	}

	/**
	 * Хелпер для построения линейного дерева (см. метод выше - linear)
	 *
	 * @author Zalatov A.
	 *
	 * @param array  $tree_relations Многомерный массив, построенный исходя из parent_id
	 * @param string $primary_key    Название атрибута, содержащего идентификатор модели
	 * @param string $parent_key     Название атрибута, содержащего идентификатор родителя
	 * @param int    $parent_id      Идентификатор родительского элемента
	 * @param int    $level          Уровень вложенности, на котором находится родительский элемент
	 * @param array  $path           Массив с родительскими элементами
	 *
	 * @return array
	 */
	private static function linear_helper($tree_relations, $primary_key, $parent_key, $parent_id, $level = 0, $path = []) {
		$result = [];

		if (isset($tree_relations[$parent_id])) {
			foreach ($tree_relations[$parent_id] as $item) {
				// -- Определяем идентификатор текущего элемента
				if (is_array($item)) {// Если это массив
					$item_id = $item[$primary_key];
				}
				else {// Если это объект
					$item_id = $item->$primary_key;
				}
				// -- -- -- --

				// -- Генерируем полный родительский путь до элемента
				$sub_path = $path;
				$sub_path[] = $item_id;
				// -- -- -- -

				// -- Добавляем элемент в результат
				$result[] = [
					'path'      => $sub_path,
					'level'     => $level,
					'pk'        => $item_id,
					'parent_id' => $parent_id,
					'item'      => $item,
				];
				// -- -- -- --

				$result = array_merge($result, self::linear_helper($tree_relations, $primary_key, $parent_key, $item_id, $level + 1, $sub_path));
			}
		}

		return $result;
	}

	/**
	 * ????
	 *
	 * @author Zalatov A.
	 *
	 * @param array  $data
	 * @param int    $id
	 * @param string $id_column
	 * @param string $parent_id_column
	 *
	 * @return int[]
	 */
	public static function GetNodeIds($data, $id, $id_column = 'id', $parent_id_column = 'parent_id') {
		$relations = self::tree_relations($data, $id_column, $parent_id_column);

		return self::getAllSubTreeIds($relations, $id, $id_column);
	}

	/**
	 * Получение списка идентификаторов для всей ветки указанного родителя
	 *
	 * @author Zalatov A.
	 *
	 * @param array  $tree_relations Массив с данными
	 * @param int    $parent_id      Идентификатор родительского элемента
	 * @param string $primary_key    Название атрибута, содержащего идентификатор модели
	 *
	 * @return array
	 */
	public static function getAllSubTreeIds($tree_relations, $parent_id, $primary_key = 'primaryKey') {
		$result = [];
		if ($parent_id !== null) {
			$result[] = $parent_id;
		}

		if (isset($tree_relations[$parent_id]))
			foreach ($tree_relations[$parent_id] as $item) {
				$result = array_merge($result, self::getAllSubTreeIds($tree_relations, $item[$primary_key], $primary_key));
			}

		return $result;
	}

	/**
	 * Функция сортирует многомерный массив по значениям элементов содержащихся в нем массивов или объектов
	 *
	 * @author Lukin A.
	 *
	 * @param array  $array    входной массив массивов или объектов для сортировки
	 * @param string $key      ключ массива или свойство объекта, по значению которого будем сортировать
	 * @param string $sort     порядок сортировки: asc/desc
	 * @param bool   $asString интерпретировать значение как текст (в этом случае 98 больше, чем 933333)
	 *
	 * @return array
	 */
	public static function sortByValue(&$array, $key, $sort = 'DESC', $asString = false) {
		uasort($array, function ($a, $b) use ($key, $sort, $asString) {
			if (is_object($a) && is_object($b)) {
				$aKey = $a->$key;
				$bKey = $b->$key;
			}
			else {
				$aKey = $a[$key];
				$bKey = $b[$key];
			}

			if ($asString) {
				if (strtolower($sort) === 'desc') {
					return strcmp($bKey, $aKey);
				}
				else {
					return strcmp($aKey, $bKey);
				}
			}
			else {
				return ($aKey - $bKey) * (strtolower($sort) === 'desc' ? -1 : 1);
			}
		});

		return $array;
	}

	/**
	 * Функция фильтрует массив моделей по определенному значению определенного атрибута.
	 *
	 * @param array  $array     Массив моделей
	 * @param string $attribute Атрибут по которому будет осуществляться поиск
	 * @param mixed  $value     Значение для фильтрации
	 *
	 * @return array        Отфильтрованный массив
	 */
	public static function filter($array, $attribute, $value) {
		$result = [];
		foreach ($array as $key => $item) {
			if ($item->$attribute == $value)
				$result[$key] = $item;
		}

		return $result;

	}
}