<?php

namespace console\controllers;

use bupy7\xml\constructor\XmlConstructor;
use common\base\helpers\StringHelper;
use common\models\oracle\scheme\sns\DspOrgs;
use yii\console\Controller;

/**
 * Экспорт данных
 *
 * @package console\controllers
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class ExportController extends Controller {

	public function actionOrgs2Mom() {
		/** @var DspOrgs[] $orgs */
		$orgs = DspOrgs::find()
			->andWhere(['IS NOT', DspOrgs::ATTR_IDAURA, null])
			->joinWith(DspOrgs::REL_ADDRESS)
			->all();

		$xml = new XmlConstructor();
		$orgNodes = [];
		foreach ($orgs as $org) {
			$addresses = [];
			foreach ($org->address as $address) {
				$addresses[] = [
					'tag'      => 'item',
					'elements' => [
						[
							'tag'     => 'type',
							'content' => 'LEGAL'
						],
						[
							'tag'     => 'zip',
							'content' => $address->ZIP
						],
						[
							'tag'     => 'country',
							'content' => 'RU'
						],
						[
							'tag'      => 'region',
							'elements' => [
								[
									'tag'      => 'item',
									'elements' => [
										[
											'tag'     => 'locale',
											'content' => 'RU',
										],
										[
											'tag'     => 'value',
											'content' => $address->REGION
										]
									]
								],
								[
									'tag'      => 'item',
									'elements' => [
										[
											'tag'     => 'locale',
											'content' => 'EN',
										],
										[
											'tag'     => 'value',
											'content' => StringHelper::transliterate($address->REGION)
										]
									]
								]
							]
						],
						[
							'tag'     => 'city',
							'content' => $address->city->IATACODE
						],
						[
							'tag'      => 'town',
							'elements' => [
								[
									'tag'      => 'item',
									'elements' => [
										[
											'tag'     => 'locale',
											'content' => 'RU',
										],
										[
											'tag'     => 'value',
											'content' => $address->city->NAME
										]
									]
								],
								[
									'tag'      => 'item',
									'elements' => [
										[
											'tag'     => 'locale',
											'content' => 'EN',
										],
										[
											'tag'     => 'value',
											'content' => $address->city->ENAME
										]
									]
								]
							]
						],
						[
							'tag'      => 'street',
							'elements' => [
								[
									'tag'      => 'item',
									'elements' => [
										[
											'tag'     => 'locale',
											'content' => 'RU',
										],
										[
											'tag'     => 'value',
											'content' => $address->STREET
										]
									]
								],
								[
									'tag'      => 'item',
									'elements' => [
										[
											'tag'     => 'locale',
											'content' => 'EN',
										],
										[
											'tag'     => 'value',
											'content' => StringHelper::transliterate($address->STREET)
										]
									]
								]
							]
						],
						[
							'tag'      => 'house',
							'elements' => [
								[
									'tag'      => 'item',
									'elements' => [
										[
											'tag'     => 'locale',
											'content' => 'RU',
										],
										[
											'tag'     => 'value',
											'content' => $address->HOUSE
										]
									]
								],
								[
									'tag'      => 'item',
									'elements' => [
										[
											'tag'     => 'locale',
											'content' => 'EN',
										],
										[
											'tag'     => 'value',
											'content' => $address->HOUSE
										]
									]
								]
							]
						],
					]
				];
			}

			$communications = [
				[
					'tag'      => 'item',
					'elements' => [
						[
							'tag'     => 'type',
							'content' => 'WEB'
						],
						[
							'tag'     => 'sense',
							'content' => $org->WEBSITE
						],
					]
				],
				[
					'tag'      => 'item',
					'elements' => [
						[
							'tag'     => 'type',
							'content' => 'EMAIL'
						],
						[
							'tag'     => 'sense',
							'content' => $org->EMAIL
						],
					]
				],
			];

			$orgNodes[] = [
				'tag'      => 'organization',
				'elements' => [
					[
						'tag'     => 'code',
						'content' => $org->IDAURA
					],
					[
						'tag'      => 'types',
						'elements' => [
							'tag'     => 'item',
							'content' => 'CORPORATE_CLIENT'
						]
					],
					[
						'tag'      => 'shortName',
						'elements' => [
							[
								'tag'      => 'item',
								'elements' => [
									[
										'tag'     => 'locale',
										'content' => 'RU'
									],
									[
										'tag'     => 'value',
										'content' => $org->NAME
									],
								]
							],
							[
								'tag'      => 'item',
								'elements' => [
									[
										'tag'     => 'locale',
										'content' => 'EN'
									],
									[
										'tag'     => 'value',
										'content' => StringHelper::transliterate($org->NAME)
									],
								]
							]
						]
					],
					[
						'tag'      => 'fullName',
						'elements' => [
							[
								'tag'      => 'item',
								'elements' => [
									[
										'tag'     => 'locale',
										'content' => 'RU'
									],
									[
										'tag'     => 'value',
										'content' => $org->NAME
									],
								]
							],
							[
								'tag'      => 'item',
								'elements' => [
									[
										'tag'     => 'locale',
										'content' => 'EN'
									],
									[
										'tag'     => 'value',
										'content' => StringHelper::transliterate($org->NAME)
									],
								]
							]
						]
					],
					[
						'tag'     => 'legalForm',
						'content' => $org->ORGFORM
					],
					[
						'tag'     => 'inn',
						'content' => $org->INN
					],
					[
						'tag'     => 'kpp',
						'content' => $org->KPP
					],
					[
						'tag'     => 'okpo',
						'content' => $org->OKPO
					],
					[
						'tag'     => 'foreign',
						'content' => false
					],
					[
						'tag'      => 'communications',
						'elements' => $communications
					],
					[
						'tag'      => 'addresses',
						'elements' => $addresses
					]
				]
			];
		}

		$in = [
			'tag'      => 'organizations',
			'elements' => $orgNodes
		];

		$xmlStr = $xml->fromArray($in)->toOutput();

		file_put_contents('orgs.xml', $xmlStr);
	}
}