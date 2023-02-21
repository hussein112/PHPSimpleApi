<?php


class DB{

    public function __construct(
        private string $host,
        private string $name,
        private string $user,
        private string $password
    )
    {
        
    }

    /**
     * @return PDO
     */
    public function getConnection(){
        $dsn = "mysql:host={$this->host};dbname={$this->name};";
        $connection = $this->connect($dsn);
        return $connection;
    }


    public function connect($dsn){
        return new PDO($dsn, $this->user, $this->password, [
            PDO::ATTR_PERSISTENT => true
        ]);
    }
}