<?php

namespace common\components\hotels;

use yii\base\Component;

class CommonAmenities extends Component {
	const OSTROVOK_AMENITIES_NAMES = [
		'addon-service'    => 'Дополнительные услуги',
		'air-conditioning' => 'Кондиционер',
		'blackout-blinds'  => 'Затемнённые шторы',
		'fridge'           => 'Холодильник',
		'hypoallergenic'   => 'Подходит для гостей с аллергией',
		'private-bathroom' => 'Собственная ванная комната',
		'shower'           => 'Душ',
		'sofa'             => 'Диван',
		'telephone'        => 'Телефон',
		'toiletries'       => 'Туалетные принадлежности',
		'tv'               => 'Телевизор',
		'wardrobe'         => 'Шкаф',
		'window'           => 'Окно',
		'with-view'        => 'Красивый вид',
		'tea'              => 'Чайник',
		'towels'           => 'Полотенца',
		'bathrobe'         => 'Махровый халат',
		'hairdryer'        => 'Фен'
	];

	/** @var string */
	public $name;
}

