<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 */

namespace common\components\excursion;

/**
 *  "id": 134910,
 * "first_name": "Мариам",
 * "url": "https://experience.tripster.ru/guide/134910/",
 * "avatar": {
 * "small": "https://experience-ireland.s3.amazonaws.com/avatar/3d1c677e-6dcd-11e8-a49f-6e714efd800d.31x31.jpg",
 * "medium": "https://experience-ireland.s3.amazonaws.com/avatar/3d100b46-6dcd-11e8-a49f-6e714efd800d.150x150.jpg"
 * },
 * "rating": 4.94568245125348,
 */
class CommonGuide {
	/** @var int */
	public $id;

	/** @var string */
	public $firstName;

	/** @var string */
	public $avatarSmall;

	/** @var string */
	public $avatarMedium;

	/** @var string */
	public $url;
}