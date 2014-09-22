<?php

namespace CarlosGoce\OdbcWrapper;

use CarlosGoce\OdbcWrapper\Exception\FetchModeDoesNotExistsException;
use CarlosGoce\OdbcWrapper\Exception\QueryException;

class Odbc
{
    /** @var resource|boolean */
    protected $connection = false;
    protected $fetchMode  = self::FETCH_AS_ARRAY;

    const FETCH_AS_ARRAY  = 1;
    const FETCH_AS_OBJECT = 2;

    /**
     * @param $dsn
     * @param string $user
     * @param string $password
     * @param null $cursorType
     * @return bool
     */
    public function connect($dsn, $user = '', $password = '', $cursorType = null)
    {
        try{
            $this->connection = odbc_connect($dsn, $user, $password, $cursorType);
        }
        catch (\Exception $e) {
            $this->connection = false;
        }

        return (boolean)$this->connection;
    }

    public function disconnect()
    {
        if ($this->connection === false) {
            return false;
        }

        odbc_close($this->connection);
        $this->connection = false;

        return true;
    }

    /**
     * @return boolean
     */
    public function isConnected()
    {
        return (bool) $this->connection;
    }

    /**
     * @param null $qualifier
     * @param null $owner
     * @param null $name
     * @param null $types
     * @return array
     */
    public function getTables($qualifier = null, $owner = null, $name = null, $types = null)
    {
        $resultSet = odbc_tables($this->connection, $qualifier, $owner, $name, $types);

        return $this->fetchResultSet($resultSet);
    }

    /**
     * Similar to getTables but only returns an array with the names of the tables and nothing else
     */
    public function getTableNames()
    {
        $tables = $this->getTables();

        $data = [];

        foreach ($tables as $table) {
            $data[] = $table['TABLENAME'];
        }

        return $data;
    }

    public function getFetchMode()
    {
        return $this->fetchMode;
    }

    public function setFetchMode($fetchMode)
    {
        if ( ! in_array($fetchMode, [self::FETCH_AS_ARRAY, self::FETCH_AS_OBJECT])) {
            throw new FetchModeDoesNotExistsException($fetchMode);
        }

        $this->fetchMode = $fetchMode;
    }

    /**
     * @param resource $resultSet
     * @return array
     */
    protected function fetchResultSet($resultSet)
    {
        $data = [];

        if ($this->getFetchMode() === self::FETCH_AS_OBJECT) {
            while ($row = odbc_fetch_object($resultSet)) {
                $data[] = $row;
            }
        }
        else {
            while ($row = odbc_fetch_array($resultSet)) {
                $data[] = $row;
            }
        }

        return $data;
    }

    /**
     * Performs a query without a prepared statement
     * @param $query
     * @throws QueryException
     * @return array
     */
    public function query($query)
    {
        $resultSet = odbc_exec($this->connection, $query);

        if ($resultSet === false) {
            throw new QueryException($this->getErrorMessage());
        }

        return $this->fetchResultSet($resultSet);
    }

    /**
     * Returns if there were errors on the last query
     * @return boolean
     */
    public function hasError()
    {
        return odbc_errormsg($this->connection) !== '';
    }

    /**
     * @return string
     */
    public function getErrorCode()
    {
        return odbc_error($this->connection);
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return odbc_errormsg($this->connection);
    }

    /**
     * Enable or disable auto commit
     * @param bool $boolean
     */
    public function setAutoCommit($boolean)
    {
        odbc_autocommit($this->connection, $boolean);
    }

    public function commit()
    {
        odbc_commit($this->connection);
    }

    public function rollback()
    {
        odbc_rollback($this->connection);
    }
}
