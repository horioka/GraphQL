<?php

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use GraphQL\Server\StandardServer;
use GraphQL\Type\Schema;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
  $queryType = Type\Query::getInstance();
  $schema = new Schema(['query' => $queryType]);
  $server = new StandardServer(['schema' => $schema]);
  $server->handleRequest();
} catch (\Exception $e) {
  StandardServer::send500Error($e);
}
