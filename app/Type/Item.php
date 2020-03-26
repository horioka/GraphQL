<?php

namespace Type;

use DataSource\Database;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class Item extends ObjectType
{
  private static $singleton;

  public static function getInstance(): self
  {
    return self::$singleton ? self::$singleton : self::$singleton = new self();
  }

  public function __construct()
  {
    $config = [
      'name' => 'Item',
      'fields' => function () {
        return [
          'id' => [
            'type' => Type::id(),
          ],
          'name' => [
            'type' => Type::string(),
          ],
          'value' => [
            'type' => Type::int(),
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
              $args['item_id'] = $value['id'];
            }
            return Query::resolveField($value, $args, $context, $info);
          default:
            return $value[$info->fieldName];
        }
      }
    ];
    parent::__construct($config);
  }

  static function getItemsField($isList = true)
  {
    return [
      'type' => $isList ? Type::listOf(Item::getInstance()) : Item::getInstance(),
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

  static function getItems($args, $isList = true)
  {
    $res = Database::fetch('items', $args, [
      ['id'],
      ['name', '%s like :%s', '%%%s%%']
    ], $isList ? 10000 : 1);
    return $isList ? $res : (empty($res) ? null : $res[0]);
  }
}
