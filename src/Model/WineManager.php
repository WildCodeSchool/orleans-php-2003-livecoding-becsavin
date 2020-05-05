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

    public function selectAll(string $search = ''): array
    {
        $query = 'SELECT w.*, r.name region_name 
                  FROM ' . self::TABLE . ' w
                  JOIN region r ON r.id=w.region_id';
        if ($search) {
            $query .= ' WHERE w.name LIKE :search';
        }
        $query .= ' ORDER BY r.name, w.name';

        $statement = $this->pdo->prepare($query);
        if ($search) {
            $statement->bindValue('search', $search . '%');
        }
        $statement->execute();

        return $statement->fetchAll();
    }

    public function insert(array $data): void
    {
        $query = 'INSERT INTO ' . self::TABLE . ' (name, producer, year, region_id, price, image) 
                  VALUES (:name, :producer, :year, :region, :price, :image)';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('name', $data['name'], \PDO::PARAM_STR);
        $statement->bindValue('producer', $data['producer'], \PDO::PARAM_STR);
        $statement->bindValue('year', $data['year'], \PDO::PARAM_INT);
        $statement->bindValue('price', $data['price']);
        $statement->bindValue('image', $data['image']);
        $statement->bindValue('region', $data['region_id'], \PDO::PARAM_INT);

        $statement->execute();
    }
}
