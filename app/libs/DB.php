<?php 

class DB {

    private $db; 

    /**
     * @var Singleton The reference to *Singleton* instance of this class
     */
    protected static $instance;
   
    private function __construct() 
    {
        $paramsPath = ROOT . '/config/db_params.php';
        $params = include($paramsPath);

        try {
                $this->db = new PDO($params['dsn'], $params['user'], $params['password'], $params['opt']);
        } catch (PDOException $e) {
                echo 'Подключение не удалось: ' . $e->getMessage();
        }
    }

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @return Singleton The *Singleton* instance.
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    protected function __clone()
    {
    }

    /**
     * Private unserialize method to prevent unserializing of the *Singleton*
     * instance.
     *
     * @return void
     */
    protected function __wakeup()
    {
    }

    public function getConnection()
    {
        return $this->db;
    }
}