<?php

namespace Core;

use PDO;
use PDOStatement;

class Database {

    private PDO $pdo;
    private PDOStatement $statement;
    private static ?Database $instance = null;

    public function __construct()
    {
        $config = require base_path('config/database.php');
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";

        try {
            $this->pdo = new PDO($dsn, $config['user'], $config['password'], $config['options']);
        } catch (\PDOException $exception) {
            die('Connection to the database has filed! ' . $exception->getMessage());
        }
    }

    //Singleton design pattern
    public static function get(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    public function query($sql, $params = []): self
    {
        $this->statement = $this->pdo->prepare($sql);

        try {
            $this->statement->execute($params);
        } catch (\PDOException $e) {
            if($e->errorInfo[1] === 1451){
                throw new ResourceInUseException();
            }
            //TODO: return 500 error page with appropriate message
            die('Something went wrong, please try again ' . $e->getMessage());
        }
       
        return $this;
    }

    public function find()
    {
       return $this->statement->fetch();
    }

    public function all()
    {
       return $this->statement->fetchAll();
    }

    public function findOrFail()
    {
        $data = $this->find();

        if (empty($data)) {
            abort();
        }

        return $data;
    }

    public function lastId()
    {
        return $this->pdo->lastInsertId();
    }

    public function connection(): PDO
    {
        return $this->pdo;
    }

    public function ukupno()
    {
       return $this->statement->rowCount();
    }
}