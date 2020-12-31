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

use Raylin666\Contract\ConnectionPoolInterface;
use Raylin666\Pool\Contract\Pool as PoolAbstract;

/**
 * Class Pool
 * @package Raylin666\Pool
 */
class Pool extends PoolAbstract
{
    /**
     * Pool constructor.
     * @param PoolConfig $config
     */
    public function __construct(PoolConfig $config)
    {
        parent::__construct($config->getConnectionCallback(), $config->getOptions());
    }

    /**
     * @return string
     */
    protected function getWithConnectionPool(): string
    {
        return Connection::class;
    }

    /**
     * @return ConnectionPoolInterface
     */
    protected function createConnection(): ConnectionPoolInterface
    {
        // TODO: Implement createConnection() method.

        return make(
            $this->getWithConnectionPool(),
            [
                'pool'      =>   $this,
                'callback'  =>   $this->getConnectionCallback()
            ]
        );
    }
}