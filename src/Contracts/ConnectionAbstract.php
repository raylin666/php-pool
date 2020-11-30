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

namespace Raylin666\Pool\Contracts;

use Raylin666\Contract\ConnectionPoolInterface;

/**
 * Class ConnectionAbstract
 * @package Raylin666\Pool\Contracts
 */
abstract class ConnectionAbstract implements ConnectionPoolInterface
{
    /**
     * 连接池
     * @var PoolAbstract
     */
    protected $pool;

    /**
     * 连接创建时间
     * @var float
     */
    protected $lastUseTime = 0.0;

    /**
     * ConnectionAbstract constructor.
     * @param PoolAbstract $pool
     */
    public function __construct(PoolAbstract $pool)
    {
        $this->pool = $pool;
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
     * 获取连接
     * @return mixed
     */
    public function getConnection()
    {
        return $this->getActiveConnection();
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
     * Connect and return the active connection
     * @return mixed
     */
    abstract public function getActiveConnection();
}