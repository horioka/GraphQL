<?php

namespace Type;

use DataSource\Database;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class Shop extends ObjectType
{
  private static $singleton;

  public static function getInstance(): self
  {
    return self::$singleton ? self::$singleton : self::$singleton = new self();
  }

  public function __construct()
  {
    $config = [
      'name' => 'Shop',
      'fields' => function () {
        return [
          'id' => [
            'type' => Type::id(),
          ],
          'name' => [
            'type' => Type::string(),
          ],
          'createdAt' => [
            'type' => Type::string(),
          ],
          'updatedAt' => [
            'type' => Type::string(),
          ],
          'getStocks' => Stock::getStocksField()
        ];
      },
      'resolveField' => function ($value, $args, $context, ResolveInfo $info) {
        switch ($info->fieldName) {
          case 'getStocks':
            if (!array_key_exists('id', $args)) {
              $args['shop_id'] = $value['id'];
            }
            return Query::resolveField($value, $args, $context, $info);
          default:
            return $value[$info->fieldName];
        }
      }
    ];
    parent::__construct($config);
  }

  static function getShopsField($isList = true)
  {
    return [
      'type' => $isList ? Type::listOf(Shop::getInstance()) : Shop::getInstance(),
      'args' => [
        'id' => [
          'type' => Type::id(),
        ],
        'name' => [
          'type' => Type::string(),
        ],
      ]
    ];
  }

  static function getShops($args, $isList = true)
  {
    $res = Database::fetch('shops', $args, [
      ['id'],
      ['name', '%s like :%s', '%%%s%%']
    ], $isList ? 10000 : 1);
    return $isList ? $res : (empty($res) ? null : $res[0]);
  }
}
