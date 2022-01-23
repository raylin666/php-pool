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

use Predis\Client;
use Raylin666\Contract\PoolOptionInterface;

/**
 * Class PoolOption
 * @package Raylin666\Pool
 */
class PoolOption implements PoolOptionInterface
{
    /**
     * Min connections of pool.
     * This means the pool will create $minConnections connections when
     * pool initialization.
     *
     * @var int
     */
    private $minConnections = 1;

    /**
     * Max connections of pool.
     *
     * @var int
     */
    private $maxConnections = 10;

    /**
     * The timeout of connect the connection.
     * Default value is 10 seconds.
     *
     * @var float
     */
    private $connectTimeout = 10.0;

    /**
     * The timeout of pop a connection.
     * Default value is 3 seconds.
     *
     * @var float
     */
    private $waitTimeout = 3.0;

    /**
     * Heartbeat of connection.
     * If the value is 10, then means 10 seconds.
     * If the value is -1, then means does not need the heartbeat.
     * Default value is -1.
     *
     * @var float
     */
    private $heartbeat = -1;

    /**
     * The max idle time for connection.
     * @var float
     */
    private $maxIdleTime = 60.0;

    /**
     * 令牌的生产方式为：每次请求进来时一次性生产上一次请求到本次请求这一段时间内的令牌
     * 限流-令牌生产速度 个/秒
     * @var int
     */
    private $limitConnectionsRate = 0;

    /**
     * redis client
     * @var
     */
    protected $redis;

    /**
     * 限流桶的名称
     * @var
     */
    private $limitName;

    public function __construct(Client  $redis)
    {
        $this->redis = $redis;
    }

    public function getRedis()
    {
        return $this->redis;
    }

    /**
     * @return int
     */
    public function getMaxConnections(): int
    {
        // TODO: Implement getMaxConnections() method.

        return $this->maxConnections;
    }

    /**
     * @param int $maxConnections
     * @return Option
     */
    public function withMaxConnections(int $maxConnections): self
    {
        $this->maxConnections = $maxConnections;
        return $this;
    }

    /**
     * @return int
     */
    public function getMinConnections(): int
    {
        // TODO: Implement getMinConnections() method.

        return $this->minConnections;
    }

    /**
     * @param int $minConnections
     * @return Option
     */
    public function withMinConnections(int $minConnections): self
    {
        $this->minConnections = $minConnections;
        return $this;
    }

    /**
     * 获取毫秒
     * @return float
     */
    private function getMillisecond(){
         list($s1, $s2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }

    /**
     * 获得lua sha
     * @return mixed
     */
    private  function getLuaSha(){
        return  $this->getRedis()->script('load', file_get_contents(__DIR__ . '/Lua/Limit.lua'));
    }

    /**
     * 设置令牌限流
     * @param int $limitConnectionsRate 每秒产生的令牌数
     * @param int $bucketCap 桶总数
     * @param int $period 限流的时间周期，单位为：秒。
     * @return $this
     */
    public function withLimitConnectionsRate(string $limitName, int $limitConnectionsRate, int $bucketCap,int $period)
    {
        $this->limitConnectionsRate = $limitConnectionsRate;
        $redis = $this->getRedis();
        $millisecond = $this->getMillisecond();
        //初始化不消耗令牌
        $redis->evalsha($this->getLuaSha(), 1, $limitName, 0, $millisecond, $bucketCap, $limitConnectionsRate, $period);
        $this->limitName = $limitName;
        return $this;
    }

    /**
     * 获得token
     * @param $configName
     * @param $tokenNum
     * @return mixed
     */
    public function getBucketToken($configName,$tokenNum)
    {
        $redis = $this->getRedis();
        $millisecond = $this->getMillisecond();
        return $redis->evalsha($this->getLuaSha(), 1, $configName, $tokenNum, $millisecond);
    }

    public function getLimitName()
    {
        return $this->limitName;
    }

    public function getLimitConnectionsRate()
    {
        return $this->limitConnectionsRate;
    }

    /**
     * @return float
     */
    public function getConnectTimeout(): float
    {
        // TODO: Implement getConnectTimeout() method.

        return $this->connectTimeout;
    }

    /**
     * @param float $connectTimeout
     * @return Option
     */
    public function withConnectTimeout(float $connectTimeout): self
    {
        $this->connectTimeout = $connectTimeout;
        return $this;
    }

    /**
     * @return float
     */
    public function getHeartbeat(): float
    {
        // TODO: Implement getHeartbeat() method.

        return $this->heartbeat;
    }

    /**
     * @param float $heartbeat
     * @return Option
     */
    public function withHeartbeat(float $heartbeat): self
    {
        $this->heartbeat = $heartbeat;
        return $this;
    }

    /**
     * @return float
     */
    public function getWaitTimeout(): float
    {
        // TODO: Implement getWaitTimeout() method.

        return $this->waitTimeout;
    }

    /**
     * @param float $waitTimeout
     * @return Option
     */
    public function withWaitTimeout(float $waitTimeout): self
    {
        $this->waitTimeout = $waitTimeout;
        return $this;
    }

    /**
     * @return float
     */
    public function getMaxIdleTime(): float
    {
        // TODO: Implement getMaxIdleTime() method.

        return $this->maxIdleTime;
    }

    /**
     * @param float $maxIdleTime
     * @return Option
     */
    public function withMaxIdleTime(float $maxIdleTime): self
    {
        $this->maxIdleTime = $maxIdleTime;
        return $this;
    }
}