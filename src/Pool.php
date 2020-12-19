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

use Raylin666\Pool\Pool\Connection;
use Raylin666\Pool\Contract\PoolAbstract;
use Raylin666\Contract\ConnectionPoolInterface;

/**
 * Class Pool
 * @package Raylin666\Pool
 */
class Pool extends PoolAbstract
{
    /**
     * 返回连接对象，例如: return new PDO(...)
     * @var callable
     */
    protected $callback;

    /**
     * 连接器类名字符串
     * @var string
     */
    protected $connection = Connection::class;

    /**
     * Pool constructor.
     * @param callable $callback    返回连接对象，例如: return new PDO(...);
     * @param array    $options     连接池配置参数项
     */
    public function __construct(callable $callback, array $options = [])
    {
        $this->callback = $callback;
        parent::__construct($options);
    }

    /**
     * 设置连接器类名字符串, 将用来实例化连接器对象
     * @param string $connectionString      Connection::class
     * @return Pool
     */
    public function withConnection(string $connectionString): self
    {
        $this->connection = $connectionString;
        return $this;
    }

    /**
     * @return ConnectionPoolInterface
     */
    protected function createConnection(): ConnectionPoolInterface
    {
        // TODO: Implement createConnection() method.

        return make(
            $this->connection,
            [
                'pool' => $this,
                'callback' => $this->callback
            ]
        );
    }
}