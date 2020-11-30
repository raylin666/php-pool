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
use Raylin666\Util\Coroutine;
use SplQueue;
use Raylin666\Util\Coroutine\Channel as UtilCoroutineChannel;

/**
 * Class Channel
 * @package Raylin666\Pool
 */
class Channel
{
    /**
     * @var int
     */
    protected $capacity = 1;

    /**
     * @var UtilCoroutineChannel
     */
    protected $channel;

    /**
     * @var SplQueue
     */
    protected $queue;

    /**
     * Channel constructor.
     * @param int $capacity
     */
    public function __construct(int $capacity = 1)
    {
        $this->capacity = $capacity;
        $this->queue = new SplQueue();
        $this->channel = make(
            UtilCoroutineChannel::class,
            [
                'capacity' => $capacity
            ]
        );
    }

    /**
     * @return ConnectionPoolInterface|false
     */
    public function pop(float $timeout)
    {
        return $this->isCoroutine()
            ? $this->channel->pop($timeout)
            : $this->queue->shift();
    }

    /**
     * @param ConnectionPoolInterface $data
     * @return bool
     */
    public function push($data): bool
    {
        if ($this->isCoroutine()) {
            return $this->channel->push($data);
        }
        $this->queue->push($data);
        return true;
    }

    /**
     * @return int
     */
    public function length(): int
    {
        return $this->isCoroutine()
            ? $this->channel->length()
            : $this->queue->count();
    }

    /**
     * @return bool
     */
    protected function isCoroutine(): bool
    {
        return Coroutine::id() > 0;
    }
}