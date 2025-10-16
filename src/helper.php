<?php

use think\facade\Queue;

if (!function_exists('queueBatch')) {
    /**
     * 批量添加任务到队列
     *
     * @param string|object $job 任务类名或对象
     * @param array $list 数据列表，每个元素将作为一个独立任务的 data
     * @param int $delay 延迟时间（秒），默认 0 立即执行
     * @param string|null $queue 队列名称，默认使用配置的队列
     * @return array 返回所有任务的 correlation IDs
     *
     * @example
     * // 立即批量推送
     * queueBatch(\app\job\SendEmail::class, [
     *     ['email' => 'user1@example.com', 'name' => 'User1'],
     *     ['email' => 'user2@example.com', 'name' => 'User2'],
     *     ['email' => 'user3@example.com', 'name' => 'User3'],
     * ]);
     *
     * // 延迟 60 秒后批量推送到指定队列
     * queueBatch(\app\job\SendSms::class, [
     *     ['phone' => '13800138000'],
     *     ['phone' => '13800138001'],
     * ], 60, 'sms_queue');
     */
    function queueBatch($job, array $list = [], int $delay = 0, ?string $queue = null): array
    {
        if (empty($list)) {
            return [];
        }

        // 构建批量任务数组
        $jobs = [];
        foreach ($list as $data) {
            $jobs[] = [
                'job' => $job,
                'data' => $data
            ];
        }

        // 获取 RabbitMQ 连接
        $connection = Queue::connection('rabbitmq');

        // 如果有延迟，使用延迟批量推送
        if ($delay > 0) {
            return $connection->laterBatch($delay, $jobs, $queue);
        }

        // 立即批量推送
        return $connection->pushBatch($jobs, $queue);
    }
}
