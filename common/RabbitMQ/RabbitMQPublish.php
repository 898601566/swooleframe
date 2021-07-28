<?php
error_reporting(E_ALL);
ini_set('display_errors', 'ON');

require_once __DIR__ . '/../../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection(
    'rabbitmq',
    5672,
    'rabbitmq',
    'Ra225851');
// 创建通道
$channel = $connection->channel();

// 创建队列
$channel->queue_declare('hello', FALSE, FALSE, FALSE, FALSE);
$msg = new AMQPMessage('Hello World!');
// 通过默认的交换机发送消息到队列 (消息内容, 默认交换机, 路由键);
$channel->basic_publish($msg, '', 'hello');
echo " [x] Sent 'Hello World!'\n";

$channel->close();
$connection->close();
