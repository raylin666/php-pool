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
 * Class PoolConfig
 * @package Raylin666\Pool
 */
class PoolConfig
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var callable
     */
    protected $connectionCallback;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * PoolConfig constructor.
     * @param string   $name
     * @param callable $connectionCallback
     * @param array    $options
     */
    public function __construct(string $name, callable $connectionCallback, array $options = [])
    {
        $this->name = $name;
        $this->options = $options;
        $this->connectionCallback = $connectionCallback;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return callable
     */
    public function getConnectionCallback(): callable
    {
        return $this->connectionCallback;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}