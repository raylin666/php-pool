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

use Psr\Container\ContainerInterface;
use Raylin666\Contract\FactoryInterface;

/**
 * Class PoolFactory
 * @package Raylin666\Pool
 */
class PoolFactory implements FactoryInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var PoolConfig[]
     */
    protected $pools = [];

    /**
     * @param ContainerInterface $container
     * @return FactoryInterface
     */
    public function __invoke(ContainerInterface $container): FactoryInterface
    {
        // TODO: Implement __invoke() method.

        $this->container = $container;
        return $this;
    }

    /**
     * @param string   $name
     * @param callable $connectionCallback
     * @param array    $options
     * @return PoolConfig
     */
    public function make(string $name, callable $connectionCallback, array $options = []): PoolConfig
    {
        if (! isset($this->pools[$name]) || ! $this->pools[$name] instanceof PoolConfig) {
            $this->pools[$name] = new PoolConfig(
                $name,
                $connectionCallback,
                $options
            );
        }

        return $this->pools[$name];
    }

    /**
     * @param string $name
     * @return PoolConfig|null
     */
    public function get(string $name): ?PoolConfig
    {
        if (isset($this->pools[$name]) && $this->pools[$name] instanceof PoolConfig) {
            return $this->pools[$name];
        }

        return null;
    }
}