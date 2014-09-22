<?php

namespace CarlosGoce\OdbcWrapper;

class Odbc
{
    protected $connection = false;

    public function connect($dsn, $user = '', $password = '', $cursorType = null)
    {
        $this->connection = odbc_connect($dsn, $user, $password, $cursorType);
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

}
