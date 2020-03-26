<?php

namespace Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

class Query extends ObjectType
{
    private static $singleton;

    public static function getInstance(): self
    {
        return self::$singleton ? self::$singleton : new self();
    }

    public function __construct()
    {
        $config = [
            'name' => 'Query',
            'fields' => [
                'getShops' => Shop::getShopsField(),
                'getShop' => Shop::getShopsField(false),
                'getItems' => Item::getItemsField(),
                'getItem' => Item::getItemsField(false),
                'getStocks' => Stock::getStocksField(),
                'getStock' => Stock::getStocksField(false)
            ],
            'resolveField' => function ($value, $args, $context, ResolveInfo $info) {
                return self::resolveField($value, $args, $context, $info);
            }
        ];
        parent::__construct($config);
    }

    static function resolveField($value, $args, $context, ResolveInfo $info)
    {
        switch ($info->fieldName) {
            case 'getShops':
                return Shop::getShops($args);
            case 'getShop':
                return Shop::getShops($args, false);
            case 'getItems':
                return Item::getItems($args);
            case 'getItem':
                return Item::getItems($args, false);
            case 'getStocks':
                return Stock::getStocks($args);
            case 'getStock':
                return Stock::getStocks($args, false);
        }
        throw new \RuntimeException('query not found');
    }
}
