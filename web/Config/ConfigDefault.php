<?php

namespace Qkor\Config;

class Config{
    public const config = [
        'debug' => false, // set to true to see exception messages
        'autoRoutes' => false, // set to true to automatically make routes from method names
        'urlPathOffset' => 1,
        'host' => '127.0.0.1',
        'dbName' => 'qkor',
        'dbUser' => 'root',
        'dbPass' => '',
        'sessionTokenValidTime' => 3600 // in seconds
    ];
}
