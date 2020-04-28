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

    public function selectAll() :array
    {
        $query = 'SELECT w.*, r.name region_name FROM ' . self::TABLE . ' w
                  JOIN region r ON r.id=w.region_id
                  ORDER BY r.name, w.name';

        return $this->pdo->query($query)->fetchAll();
    }

    public function insert(array $data) :void
    {
        $query = 'INSERT INTO ' . self::TABLE . ' (name, producer, year, region_id) 
                  VALUES (:name, :producer, :year, :region)';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('name', $data['name'], \PDO::PARAM_STR);
        $statement->bindValue('producer', $data['producer'], \PDO::PARAM_STR);
        $statement->bindValue('year', $data['year'], \PDO::PARAM_INT);
        $statement->bindValue('region', $data['region_id'], \PDO::PARAM_INT);

        $statement->execute();
    }
}
