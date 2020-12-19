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

namespace Raylin666\Pool\Pool;

use Raylin666\Pool\Contract\ConnectionAbstract;

/**
 * Class Connection
 * @package Raylin666\Pool\Pool
 */
class Connection extends ConnectionAbstract
{
    /**
     * 获取可活跃的连接
     * @return mixed
     */
    public function getActiveConnection()
    {
        // TODO: Implement getActiveConnection() method.

        // 是否需要重连, 需要则重连
        if ($this->isReconnectConnection()) {
            $this->reconnect();
        }

        return $this->connection;
    }
}