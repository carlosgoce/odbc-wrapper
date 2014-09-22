<?php

namespace unit\CarlosGoce\OdbcWrapper;

use CarlosGoce\OdbcWrapper\Odbc;
use PHPUnit_Framework_TestCase;

class OdbcTest extends PHPUnit_Framework_TestCase
{
    /** @var Odbc */
    protected $odbc;
    protected $dsn = 'vfp86';

    function __construct()
    {
        $this->odbc = new Odbc();
    }

    public function testCanConnectToDatabase()
    {
        $this->assertTrue( $this->odbc->connect($this->dsn) );
        $this->assertFalse($this->odbc->connect('wrongDsn'));
    }

    public function testCanDisconnectFromDatabase()
    {
        $this->assertFalse($this->odbc->disconnect());
        $this->assertTrue($this->odbc->connect($this->dsn));
        $this->assertTrue($this->odbc->connect($this->dsn));
    }



}