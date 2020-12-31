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

use Raylin666\Pool\Contract\Connection as ConnectionAbstract;

/**
 * Class Connection
 * @package Raylin666\Pool
 */
class Connection extends ConnectionAbstract
{
    /**
     * @return mixed|null
     */
    protected function getActiveConnection()
    {
        // TODO: Implement getActiveConnection() method.

        return $this->reconnect();
    }
}