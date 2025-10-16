<?php

namespace hanhan\ThinkQueueRabbitMQ\queue;

use hanhan\ThinkQueueRabbitMQ\queue\command\Consume;
use hanhan\ThinkQueueRabbitMQ\queue\command\ExchangeDeclare;
use hanhan\ThinkQueueRabbitMQ\queue\command\ExchangeDelete;
use hanhan\ThinkQueueRabbitMQ\queue\command\QueueBind;
use hanhan\ThinkQueueRabbitMQ\queue\command\QueueDeclare;
use hanhan\ThinkQueueRabbitMQ\queue\command\QueueDelete;
use hanhan\ThinkQueueRabbitMQ\queue\command\QueuePurge;

class Service extends \think\Service
{
    public function register()
    {
        $config = require __DIR__.'/config.php';

        $queueConfig = $this->app->config->get('queue', []);
        $queueConfig['connections'] = array_merge(
            $config,
            $queueConfig['connections'] ?? []
        );

        $this->app->config->set($queueConfig, 'queue');
    }

    public function boot()
    {
        $this->commands([
            Consume::class,
            ExchangeDeclare::class,
            ExchangeDelete::class,
            QueueBind::class,
            QueueDeclare::class,
            QueueDelete::class,
            QueuePurge::class,
        ]);
    }
}
