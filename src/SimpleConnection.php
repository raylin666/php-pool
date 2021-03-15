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

/**
 * Class SimpleConnection
 * @package Raylin666\Pool
 */
class SimpleConnection extends Connection
{
    /**
     * @return mixed
     */
    protected function getActiveConnection()
    {
        // TODO: Implement getActiveConnection() method.

        return $this->reconnect();
    }
}