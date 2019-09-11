<?php
namespace console\controllers;

use bupy7\xml\constructor\XmlConstructor;
use common\models\oracle\scheme\sns\DspOrgs;
use yii\console\Controller;

/**
 * Экспорт данных
 *
 * @noinspection PhpUnused
 *
 * @package console\controllers
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class ExportController extends Controller {

	/** @noinspection PhpUnused */

	/**
	 * Выгрузка справочника организаций из ДСП в Mid Office Manager
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionOrgs2Mom() {
		/** @var DspOrgs[] $orgs */
		$orgs = DspOrgs::find()
			->andWhere(['IS NOT', DspOrgs::ATTR_IDAURA, null]) //может и не нужно
			->joinWith(DspOrgs::REL_ADDRESS)
			->joinWith(DspOrgs::REL_SABRE_ID, true, 'INNER JOIN')
			->all();

		$xml = new XmlConstructor();
		$orgNodes = [];
		foreach ($orgs as $org) {
			$addresses = [];
			foreach ($org->address as $address) {
				if (null === $address->city) {
					continue;
				}

				$addressType = trim($address->ADDRTYPE);
				if ($addressType == 'POST') {
					$addressType = 'CONTACT';
				}

				$addresses[] = [
					'tag'      => 'item',
					'elements' => [
						[
							'tag'     => 'type',
							'content' => $addressType
						],
						[
							'tag'     => 'zip',
							'content' => trim($address->ZIP)
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
											'content' => trim($address->REGION)
										]
									]
								]
							]
						],
						[
							'tag'     => 'city',
							'content' => trim($address->city->IATACODE)
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
											'content' => trim($address->city->NAME)
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
											'content' => trim($address->city->ENAME)
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
											'content' => trim($address->STREET)
										]
									]
								],
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
											'content' => trim($address->HOUSE)
										]
									]
								],
							]
						],
					]
				];
			}

			$communications = [];
			if (!empty(trim($org->WEBSITE))) {
				$communications[] = [
					'tag'      => 'item',
					'elements' => [
						[
							'tag'     => 'type',
							'content' => 'WEB'
						],
						[
							'tag'     => 'sense',
							'content' => trim($org->WEBSITE)
						],
					]
				];
			}

			if (!empty(trim($org->EMAIL))) {
				$communications[] = [
					'tag'      => 'item',
					'elements' => [
						[
							'tag'     => 'type',
							'content' => 'EMAIL'
						],
						[
							'tag'     => 'sense',
							'content' => trim($org->EMAIL)
						],
					]
				];
			}

			$orgNodes[] = [
				'tag'      => 'organization',
				'elements' => [
					[
						'tag'     => 'code',
						'content' => trim($org->sabreId->EXTID)
					],
					[
						'tag'      => 'types',
						'elements' => [
							[
								'tag'     => 'item',
								'content' => 'CORPORATE_CLIENT'
							]
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
										'content' => trim($org->NAME)
									],
								]
							],
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
										'content' => trim($org->NAME)
									],
								]
							],
						]
					],
					[
						'tag'     => 'legalForm',
						'content' => trim($org->ORGFORM)
					],
					[
						'tag'     => 'inn',
						'content' => trim($org->INN)
					],
					[
						'tag'     => 'kpp',
						'content' => trim($org->KPP)
					],
					[
						'tag'     => 'okpo',
						'content' => trim($org->OKPO)
					],
					[
						'tag'     => 'foreign',
						'content' => 'false'
					],
					[
						'tag'      => 'communications',
						'elements' => $communications
					],
					[
						'tag'      => 'addresses',
						'elements' => $addresses
					],
					[
						'tag'      => 'metadata',
						'elements' => [
							[
								'tag'      => 'item',
								'elements' => [
									[
										'tag'     => 'key',
										'content' => 'KEY_ACCOUNTING_SYSTEM_CODE'
									],
									[
										'tag'     => 'value',
										'content' => trim($org->ID1C)
									],
									[
										'tag'     => 'remarks',
										'content' => 'Код в 1С'
									]
								]
							]
						]
					]
				]
			];
		}

		$in = [
			[
				'tag'      => 'organizations',
				'elements' => $orgNodes
			]
		];

		$xmlStr = $xml->fromArray($in)->toOutput();

		file_put_contents('orgs.xml', $xmlStr);
	}
}