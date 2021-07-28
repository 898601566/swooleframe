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
echo " [*] Waiting for messages. To exit press CTRL+C\n";
$callback = function ($msg) {
    echo ' [x] Received ', $msg->body, "\n";
};
// 通过默认的交换机发送消息到队列 (消息内容, 默认交换机, 路由键);
$channel->basic_consume('hello', '', FALSE, TRUE,
    FALSE, FALSE, $callback);
while ($channel->is_consuming()) {
    $channel->wait();
}
$channel->close();
$connection->close();
