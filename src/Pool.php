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
use Raylin666\Pool\Contracts\PoolAbstract;

/**
 * Class Pool
 * @package Raylin666\Pool
 */
class Pool extends PoolAbstract
{
    /**
     * @var callable
     */
    protected $callback;

    /**
     * Pool constructor.
     * @param callable $callback    返回连接对象,例如 return new PDO(...);
     * @param array    $config
     */
    public function __construct(callable $callback, array $config = [])
    {
        $this->callback = $callback;
        parent::__construct($config);
    }

    /**
     * @return ConnectionPoolInterface
     */
    protected function createConnection(): ConnectionPoolInterface
    {
        // TODO: Implement createConnection() method.

        return make(
            Connection::class,
            [
                'pool' => $this,
                'callback' => $this->callback
            ]
        );
    }
}