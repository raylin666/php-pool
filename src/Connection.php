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

namespace Raylin666\Pool;

use Raylin666\Pool\Contracts\ConnectionAbstract;

/**
 * Class Connection
 * @package Raylin666\Pool
 */
class Connection extends ConnectionAbstract
{
    /**
     * @var callable
     */
    public $callback;

    /**
     * @var connection
     */
    public $connection;

    /**
     * Connection constructor.
     * @param Pool     $pool
     * @param callable $callback
     */
    public function __construct(Pool $pool, callable $callback)
    {
        $this->callback = $callback;
        parent::__construct($pool);
    }

    /**
     * Connect and return the active connection
     * @return mixed
     */
    public function getActiveConnection()
    {
        // TODO: Implement getActiveConnection() method.

        if (! $this->connection || ! $this->check()) {
            $this->reconnect();
        }

        return $this->connection;
    }

    /**
     * @return bool
     */
    public function reconnect(): bool
    {
        $this->connection = $this->connect();
        $this->lastUseTime = microtime(true);
        return true;
    }

    /**
     * 得到连接
     * @return mixed
     */
    protected function connect()
    {
        return ($this->callback)();
    }

    /**
     * @return bool
     */
    public function close(): bool
    {
        $this->connection = null;
        return true;
    }
}