<?php 

return array(
    'dsn'      => 'mysql:dbname=market;host=localhost', 
    'user'     => 'root', 
    'password' => '30061504', 
    'opt'      => [
                   PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                   PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                  ]
);