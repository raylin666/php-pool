# Pool 连接池 - Swoole 协程

[![GitHub release](https://img.shields.io/github/release/raylin666/pool.svg)](https://github.com/raylin666/pool/releases)
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

require_once 'vendor/autoload.php';

$container = new \Raylin666\Container\Container();

$container->singleton(\Raylin666\Contract\PoolInterface::class, function ($container) {
    $factory = new \Raylin666\Pool\PoolFactory;
    return $factory($container);
});

$container->get(\Raylin666\Contract\PoolInterface::class)->make(
    'db.mysql',
    function () {
        return 'connection';
    },
    [
        'max_connections' => 50
    ]
);

$pool = new Raylin666\Pool\Pool(
    $container->get(\Raylin666\Contract\PoolInterface::class)->get('db.mysql')
);

$connectionPool = $pool->get();     

$connectionPool->getConnection();   // 输出: string(10) "connection"

$connectionPool->release();

```

### 更新日志

请查看 [CHANGELOG.md](CHANGELOG.md)

### 贡献

非常欢迎感兴趣，愿意参与其中，共同打造更好PHP生态，Swoole生态的开发者。

* 在你的系统中使用，将遇到的问题 [反馈](https://github.com/raylin666/pool/issues)

### 联系

如果你在使用中遇到问题，请联系: [1099013371@qq.com](mailto:1099013371@qq.com). 博客: [kaka 梦很美](http://www.ls331.com)

## License MIT
