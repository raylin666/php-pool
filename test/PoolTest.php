<?php

use Raylin666\Pool\PoolConfig;
use Raylin666\Pool\PoolOption;

require_once __DIR__ . '/../vendor/autoload.php';

class PoolTest extends \PHPUnit\Framework\TestCase
{
    public function testMaxPool()
    {
        $localhost_ip = '192.168.2.35';
        $dbms = 'mysql';     //数据库类型
        $host = $localhost_ip.':3306'; //数据库主机名
//        $host = 'localhost:3306'; //数据库主机名
        $dbName = 'my_test';    //使用的数据库
        $user = 'root';      //数据库连接用户名
        $pass = 'root';          //对应的密码
        $dsn = "$dbms:host=$host;dbname=$dbName";
        $name = 'database';

        $redis_client = new \Predis\Client("tcp://{$localhost_ip}:6379");

        for ($i=0;$i<1;$i++) {
            echo $i . PHP_EOL;
            $poolConfig = new PoolConfig(
                $name,
                function () use ($pass, $user, $dsn) {
                    // 一般返回连接对象, 例如: return new PDO(...);
                    return new PDO($dsn, $user, $pass);
                },
                (new PoolOption($redis_client))
                    ->withMinConnections(10)
                    ->withLimitConnectionsRate('new_limit_name', 15, 300, 600)
                    ->withMaxConnections(100)
            );
        }
        /**
         * 扩展提供了 SimplePool 和 SimpleConnection 类可以直接使用简单的连接池.
         * 也可以继承 Pool 和 Connection, 实现相对复杂的业务连接池
         */

        $pool = new \Raylin666\Pool\SimplePool($poolConfig);    // 创建连接池
        for ($i=0;$i<100;$i++){
            echo '循环次数：'.$i . PHP_EOL;
            $conn = $pool->get();                                   // 获取连接
            $db = $conn->getConnection();                                 // 获取连接内容, 即连接对象
        }
//        $res = $db->query('select * from yi_user where id = 1');
        foreach ($db->query('select * from yi_user where id = 1') as $row) {
            var_dump($row);
        }
        $conn->release();                                       // 连接发布(放回连接池)
//        var_dump($pool);;
    }
}