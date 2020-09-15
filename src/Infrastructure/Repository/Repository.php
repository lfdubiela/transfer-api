<?php

namespace App\Infrastructure\Repository;

use Doctrine\DBAL\Connection;

abstract class Repository implements IRepository
{
    private Connection $connection;

    /**
     * Repository constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return Connection
     */
    public function getConnection(): Connection
    {
        return $this->connection;
    }
}
