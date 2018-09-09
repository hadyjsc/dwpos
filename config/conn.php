<?php
ini_set("display_errors", "1");
error_reporting(E_ALL);
$file = "vendor/autoload.php";

if (file_exists($file)) {
    include $file;
}else if(file_exists("../".$file)) {
    include '../'.$file;
}else {
    include '../../'.$file;
}

use Marquine\Etl\Etl;


$config = array(
            'driver'    => 'mysql', 
            'host'      => 'localhost',
            'database'  => 'db_dwpos',
            'username'  => 'root',
            'password'  => 'root',
            'charset'   => 'utf8', 
            'collation' => 'utf8_unicode_ci', 
            'prefix'    => '', 
            'options'   => array( 
                PDO::ATTR_TIMEOUT => 5,
                PDO::ATTR_EMULATE_PREPARES => false,
            ),
        );


$itlconf = [
    // If not provided, you can use the full path when working with files.
    'path' => '/path/to/etl/files',
    'database' => [
        'default' => 'mysql',
        'connections' => [
            'mysql' => [
                'driver' => 'mysql',
                'host' => 'localhost',
                'port' => '3306',
                'database' => 'db_dwpos',
                'username' => 'root',
                'password' => 'root',
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
            ],
        ],
    ],
];

new \Pixie\Connection('mysql', $config, 'QB');

Etl::config($itlconf);



?>