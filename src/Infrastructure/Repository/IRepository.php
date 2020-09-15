<?php

namespace App\Infrastructure\Repository;

use Doctrine\DBAL\Connection;

interface IRepository
{
    /**
     * @return Connection
     */
    public function getConnection(): Connection;
}
