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

use Raylin666\Contract\ConnectionPoolInterface;

/**
 * Class ConnectionAbstract
 * @package Raylin666\Pool\Contract
 */
abstract class ConnectionAbstract implements ConnectionPoolInterface
{
    /**
     * 连接池
     * @var PoolAbstract
     */
    protected $pool;

    /**
     * @var callable
     */
    protected $callback;

    /**
     * 具体连接对象
     * @var
     */
    protected $connection;

    /**
     * 连接创建时间
     * @var float
     */
    protected $lastUseTime = 0.0;

    /**
     * ConnectionAbstract constructor.
     * @param PoolAbstract $pool
     * @param callable     $callback    返回连接对象，例如: return new PDO(...);
     */
    public function __construct(PoolAbstract $pool, callable $callback)
    {
        $this->pool = $pool;
        $this->callback = $callback;
    }

    /**
     * @return PoolAbstract
     */
    public function getPool(): PoolAbstract
    {
        return $this->pool;
    }

    /**
     * @return float
     */
    public function getLastUseTime(): float
    {
        return $this->lastUseTime;
    }

    /**
     * @return callable
     */
    public function getCallback(): callable
    {
        return $this->callback;
    }

    /**
     * @return mixed
     */
    protected function getConnectionObject()
    {
        return ($this->callback)();
    }

    /**
     * @return bool
     */
    public function reconnect(): bool
    {
        // TODO: Implement reconnect() method.

        $this->connection = $this->getConnectionObject();
        $this->lastUseTime = microtime(true);
        return true;
    }

    /**
     * 发布连接
     * release connection
     */
    public function release(): void
    {
        $this->pool->release($this);
    }

    /**
     * 检查空闲连接,更新最新连接时间
     * @return bool
     */
    public function check(): bool
    {
        $maxIdleTime = $this->pool->getOption()->getMaxIdleTime();
        $now = microtime(true);
        if ($now > $maxIdleTime + $this->lastUseTime) {
            return false;
        }

        $this->lastUseTime = $now;
        return true;
    }

    /**
     * @return bool
     */
    public function close(): bool
    {
        // TODO: Implement close() method.

        $this->connection = null;
        return true;
    }

    /**
     * 是否需要重连,如需要 则调用 $this->reconnect()
     * @return bool
     */
    protected function isReconnectConnection(): bool
    {
        if (empty($this->connection) || (! $this->check())) {
            return true;
        }

        return false;
    }

    /**
     * 获取连接
     * @return mixed
     */
    public function getConnection()
    {
        return $this->getActiveConnection();
    }

    /**
     * Connect and return the active connection
     * @return mixed
     */
    abstract public function getActiveConnection();
}