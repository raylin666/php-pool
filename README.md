# Pool 通用连接池 - Swoole 协程

[![GitHub release](https://img.shields.io/github/release/raylin666/php-pool.svg)](https://github.com/raylin666/php-pool/releases)
[![PHP version](https://img.shields.io/badge/php-%3E%207.2-orange.svg)](https://github.com/php/php-src)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](#LICENSE)

### 环境要求

* PHP >=7.2

### 安装说明

```
composer require "raylin666/pool"
```

### 使用方式

```php
<?php

require 'vendor/autoload.php';

use Raylin666\Pool\SimplePool;
use Raylin666\Pool\PoolConfig;
use Raylin666\Pool\PoolOption;

$poolConfig = new PoolConfig(
            'database',
            function () {
                // 一般返回连接对象, 例如: return new PDO(...);
                return 'mysql';
            },
            (new PoolOption())
                ->withMinConnections(10)
                ->withMaxConnections(100)
        );

/**
 * 扩展提供了 SimplePool 和 SimpleConnection 类可以直接使用简单的连接池.
 * 也可以继承 Pool 和 Connection, 实现相对复杂的业务连接池
 */

$pool = new \Raylin666\Pool\SimplePool($poolConfig);    // 创建连接池
$conn = $pool->get();                                   // 获取连接
$conn->getConnection();                                 // 获取连接内容, 即连接对象
$conn->release();                                       // 连接发布(放回连接池)
var_dump($pool);

```

欢迎阅读源码.

### 更新日志

请查看 [CHANGELOG.md](CHANGELOG.md)

### 联系

如果你在使用中遇到问题，请联系: [1099013371@qq.com](mailto:1099013371@qq.com). 博客: [kaka 梦很美](http://www.ls331.com)

## License MIT
