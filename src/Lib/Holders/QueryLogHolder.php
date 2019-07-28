<?php

declare(strict_types=1);

namespace Performance\Lib\Holders;

use function array_flip;
use function strtolower;
use function substr;

/**
 * @todo needs refactoring
 *
 * Class QueryLogHolder
 * @package Performance\Lib\Holders
 */
class QueryLogHolder
{
    protected const QUERYTYPE = [
        'select',
        'insert',
        'update',
        'delete'
    ];

    /**
     * @var
     */
    private $queryType;

    /**
     * @var string
     */
    private $query = '';

    /**
     * @var array
     */
    private $bindings;

    /**
     * @var int
     */
    private $time = 0;

    /**
     * @var
     */
    private $driver;

    /**
     * @var
     */
    private $database;

    /**
     * @var
     */
    private $host;

    /**
     * @var
     */
    private $port;

    /**
     * @var
     */
    private $username;

    /**
     * @var
     */
    private $prefix;

    /**
     * @var array
     */
    private $connectionName;

    /**
     * QueryLogHolder constructor.
     * @param $sql
     */
    public function __construct($sql)
    {
        $this->time = $sql->time ?? null;
        $this->query = $sql->sql ?? '';
        $this->bindings = $sql->bindings ?? [];

        $connection = $sql->connection;

// null fix change https://github.com/illuminate/database/commit/ba465fbda006d70265362012653b4e97667c867b#diff-eba180ff89e23df156c82c995be952df
        $config = method_exists($connection, 'getConfig') ? $sql->connection->getConfig(null) : [];

        $this->connectionName = method_exists($connection, 'getName') ? $sql->connection->getName() : [];
        $this->setUpConnection($connection, $config);

        $this->checkQueryType();
    }

    /**
     * Extract and set query type
     */
    protected function checkQueryType()
    {
        $flipped = array_flip(self::QUERYTYPE);
        $this->queryType = $flipped[ strtolower(substr($this->query, 0, 6)) ] ?? 'unknown';
    }

    /**
     * @param $connection
     * @param array $config
     */
    public function setUpConnection($connection, array $config): void
    {
        if (method_exists($connection, 'getConfig')) {
            $this->database = $config[ 'database' ] ?? null;
            $this->driver = $config[ 'driver' ] ?? null;
            $this->host = $config[ 'host' ] ?? null;
            $this->port = $config[ 'port' ] ?? null;
            $this->username = $config[ 'username' ] ?? null;
            $this->prefix = $config[ 'prefix' ] ?? null;
        }
    }

    /**
     * @return mixed
     */
    public function getQueryType()
    {
        return $this->queryType;
    }

    /**
     * @param $queryType
     * @return QueryLogHolder
     */
    public function setQueryType($queryType): QueryLogHolder
    {
        $this->queryType = $queryType;

        return $this;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param $query
     * @return QueryLogHolder
     */
    public function setQuery(string $query): QueryLogHolder
    {
        $this->query = $query;

        return $this;
    }

    /**
     * @return array
     */
    public function getBindings(): array
    {
        return $this->bindings;
    }

    /**
     * @param array $bindings
     * @return QueryLogHolder
     */
    public function setBindings(array $bindings): QueryLogHolder
    {
        $this->bindings = $bindings;

        return $this;
    }

    /**
     * @return int
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param $time
     * @return QueryLogHolder
     */
    public function setTime(int $time): QueryLogHolder
    {
        $this->time = $time;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @param $driver
     * @return QueryLogHolder
     */
    public function setDriver($driver): QueryLogHolder
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * @param $database
     * @return QueryLogHolder
     */
    public function setDatabase($database): QueryLogHolder
    {
        $this->database = $database;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param $host
     * @return QueryLogHolder
     */
    public function setHost($host): QueryLogHolder
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param $port
     * @return QueryLogHolder
     */
    public function setPort($port): QueryLogHolder
    {
        $this->port = $port;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param $username
     * @return QueryLogHolder
     */
    public function setUsername($username): QueryLogHolder
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param $prefix
     * @return QueryLogHolder
     */
    public function setPrefix($prefix): QueryLogHolder
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * @return array
     */
    public function getConnectionName(): array
    {
        return $this->connectionName;
    }

    /**
     * @param array $connectionName
     * @return QueryLogHolder
     */
    public function setConnectionName(array $connectionName): QueryLogHolder
    {
        $this->connectionName = $connectionName;

        return $this;
    }
}
