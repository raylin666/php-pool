<?php
// +----------------------------------------------------------------------
// | Created by linshan. 版权所有 @
// +----------------------------------------------------------------------
// | Copyright (c) 2020 All rights reserved.
// +----------------------------------------------------------------------
// | Technology changes the world . Accumulation makes people grow .
// +----------------------------------------------------------------------
// | Author: kaka梦很美 <1099013371@qq.com>
// +----------------------------------------------------------------------

namespace Raylin666\Pool\Contract;

use Throwable;
use RuntimeException;
use Raylin666\Util\Queue;
use Raylin666\Pool\Pool\Option;
use Raylin666\Contract\PoolInterface;
use Raylin666\Contract\PoolOptionInterface;
use Raylin666\Contract\ConnectionPoolInterface;

/**
 * Class PoolAbstract
 * @package Raylin666\Pool\Contract
 */
abstract class PoolAbstract implements PoolInterface
{
    /**
     * @var Queue
     */
    protected $queue;

    /**
     * @var PoolOptionInterface
     */
    protected $option;

    /**
     * @var int
     */
    protected $currentConnections = 0;

    /**
     * PoolAbstract constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->initOption($options);

        $this->queue = make(
            Queue::class,
            [
                'capacity' => $this->option->getMaxConnections()
            ]
        );

        $this->initConnection();
    }

    /**
     * @return PoolOptionInterface
     */
    public function getOption(): PoolOptionInterface
    {
        return $this->option;
    }

    /**
     * @return Queue
     */
    public function getQueue(): Queue
    {
        return $this->queue;
    }

    /**
     * @return int
     */
    public function getCurrentConnections(): int
    {
        return $this->currentConnections;
    }

    /**
     * @return int
     */
    public function getConnectionsNum(): int
    {
        return $this->queue->length();
    }

    /**
     * @return ConnectionPoolInterface
     * @throws Throwable
     */
    public function get(): ConnectionPoolInterface
    {
        // TODO: Implement get() method.

        $num = $this->getConnectionsNum();

        try {
            if ($num === 0 && $this->currentConnections < $this->option->getMaxConnections()) {
                ++$this->currentConnections;
                return $this->createConnection();
            }
        } catch (Throwable $e) {
            --$this->currentConnections;
            throw $e;
        }

        $connection = $this->queue->pop($this->option->getWaitTimeout());

        if (! $connection instanceof ConnectionPoolInterface) {
            throw new RuntimeException('Connection pool exhausted. Cannot establish new connection before wait_timeout.');
        }

        return $connection;
    }

    /**
     * @param ConnectionPoolInterface $connectionPool
     */
    public function release(ConnectionPoolInterface $connectionPool): void
    {
        // TODO: Implement release() method.

        $this->queue->push($connectionPool);
    }

    /**
     * flush queue connections pool
     */
    public function flush(): void
    {
        // TODO: Implement flush() method.

        $num = $this->getConnectionsNum();

        if ($num > 0) {
            while ($this->currentConnections > $this->option->getMinConnections() && $conn = $this->queue->pop(0.001)) {
                try {
                    $conn->close();
                } catch (Throwable $e) {
                    // ...
                } finally {
                    --$this->currentConnections;
                    --$num;
                }

                if ($num <= 0) {
                    // Ignore connections queued during flushing.
                    break;
                }
            }
        }
    }

    /**
     * @param bool $must
     */
    public function flushOne(bool $must = false): void
    {
        $num = $this->getConnectionsNum();

        if ($num > 0 && $conn = $this->queue->pop(0.001)) {
            if ($must || ! $conn->check()) {
                try {
                    $conn->close();
                } catch (Throwable $e) {
                    // ...
                } finally {
                    --$this->currentConnections;
                }
            } else {
                $this->release($conn);
            }
        }
    }

    /**
     * @return ConnectionPoolInterface
     */
    abstract protected function createConnection(): ConnectionPoolInterface;

    /**
     * 初始化连接池配置
     * @param array $options
     */
    protected function initOption(array $options = []): void
    {
        $minConnections = $options['min_connections'] ?? 10;
        $maxConnections = $options['max_connections'] ?? 100;
        if ($minConnections > $maxConnections) {
            $minConnections = $maxConnections - 1;
        }

        $this->option = make(
            Option::class,
            [
                'minConnections' => $minConnections,
                'maxConnections' => $maxConnections,
                'connectTimeout' => $options['connect_timeout'] ?? 10.0,
                'waitTimeout'    => $options['wait_timeout']    ?? 3.0,
                'heartbeat'      => $options['heartbeat']       ?? -1,
                'maxIdleTime'    => $options['max_idle_time']   ?? 60.0,
            ]
        );
    }

    /**
     * 初始化连接池
     */
    protected function initConnection()
    {
        if ($this->getConnectionsNum() === 0 && $this->currentConnections === 0) {
            for ($i = $this->option->getMinConnections(); $i--;) {
                ++$this->currentConnections;
                $this->release(
                    $this->createConnection()
                );
            }
        }
    }
}