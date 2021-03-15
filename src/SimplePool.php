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

/**
 * Class SimplePool
 * @package Raylin666\Pool
 */
class SimplePool extends Pool
{
    /**
     * @return ConnectionPoolInterface
     */
    protected function createConnection(): ConnectionPoolInterface
    {
        // TODO: Implement createConnection() method.

        return make(
            SimpleConnection::class,
            [
                'pool' => $this,
                'callback' => $this->getConnectionCallback(),
            ]
        );
    }
}