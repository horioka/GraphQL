<?php

namespace Type;

use DataSource\Database;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class Stock extends ObjectType
{
  private static $singleton;

  public static function getInstance(): self
  {
    return self::$singleton ? self::$singleton : self::$singleton = new self();
  }

  public function __construct()
  {
    $config = [
      'name' => 'Stock',
      'fields' => function () {
        return [
          'id' => [
            'type' => Type::id(),
          ],
          'shop_id' => [
            'type' => Type::id(),
          ],
          'item_id' => [
            'type' => Type::id(),
          ],
          'count' => [
            'type' => Type::int(),
          ],
          'created_at' => [
            'type' => Type::string(),
          ],
          'updated_at' => [
            'type' => Type::string(),
          ],
          'getShop' => Shop::getShopsField(false),
          'getItem' => Item::getItemsField(false),
        ];
      },
      'resolveField' => function ($value, $args, $context, ResolveInfo $info) {
        switch ($info->fieldName) {
          case 'getShop':
            if (!array_key_exists('id', $args)) {
              $args['id'] = $value['shop_id'];
            }
            return Query::resolveField($value, $args, $context, $info);
          case 'getItem':
            if (!array_key_exists('id', $args)) {
              $args['id'] = $value['item_id'];
            }
            return Query::resolveField($value, $args, $context, $info);
          default:
            return $value[$info->fieldName];
        }
      }
    ];
    parent::__construct($config);
  }

  static function getStocksField($isList = true)
  {
    return [
      'type' => $isList ? Type::listOf(Stock::getInstance()) : Stock::getInstance(),
      'args' => [
          'id' => [
              'type' => Type::id(),
          ],
          'shop_id' => [
              'type' => Type::id(),
          ],
          'item_id' => [
              'type' => Type::id(),
          ],
      ]
    ];
  }

  static function getStocks($args, $isList = true)
  {
    $res = Database::fetch('stocks', $args, [
      ['id'],
      ['shop_id'],
      ['item_id'],
    ], $isList ? 10000 : 1);
    return $isList ? $res : (empty($res) ? null : $res[0]);
  }
}
