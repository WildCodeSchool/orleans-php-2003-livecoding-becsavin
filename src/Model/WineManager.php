<?php
/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 18:20
 * PHP version 7
 */

namespace App\Model;

/**
 *
 */
class WineManager extends AbstractManager
{

    const TABLE = 'wine';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function insert(array $data) :void
    {
        $query = 'INSERT INTO ' . self::TABLE . ' (name, producer, year) VALUES (:name, :producer, :year)';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('name', $data['name'], \PDO::PARAM_STR);
        $statement->bindValue('producer', $data['producer'], \PDO::PARAM_STR);
        $statement->bindValue('year', $data['year'], \PDO::PARAM_INT);

        $statement->execute();
    }
}
