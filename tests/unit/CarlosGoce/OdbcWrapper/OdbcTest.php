<?php

namespace unit\CarlosGoce\OdbcWrapper;

use CarlosGoce\OdbcWrapper\Odbc;
use PHPUnit_Framework_TestCase;

/**
 * Class OdbcTest
 * @package unit\CarlosGoce\OdbcWrapper
 *
 * Test needs Microsoft's VisualFoxPro driver in order to work
 * or an Advantage Database Server with a configured DSN named AdsTest pointing to tests/fixtures/database/temp
 */
class OdbcTest extends PHPUnit_Framework_TestCase
{
    /** @var Odbc */
    protected $odbc;
    protected $dsn;

    function __construct()
    {
//        $this->dsn = "Driver={Microsoft Visual FoxPro Driver};SourceType=DBF;SourceDB=". db_path() .";Exclusive =No;";
        $this->dsn = 'AdsTest';
    }

    public function setUp()
    {
        $this->cleanDatabase();

        $this->odbc = new Odbc();
        $this->odbc->connect($this->dsn);
    }

    public function cleanDatabase()
    {
        $zipFile = fixtures_path('database/clean/tables.zip');

        if ( ! is_file($zipFile)) {
            throw new \Exception('File not found: ' . $zipFile);
        }

        $zip = new \ZipArchive();
        $zip->open($zipFile);
        $zip->extractTo(fixtures_path('database/temp/'));
    }

    public function testCanConnectToDatabase()
    {
        $this->assertTrue($this->odbc->disconnect());
        $this->assertTrue($this->odbc->connect($this->dsn));
        $this->assertFalse($this->odbc->connect('wrongDsn'));
    }

    public function testCanDisconnectFromDatabase()
    {
        $this->assertTrue($this->odbc->isConnected());
        $this->assertTrue($this->odbc->disconnect());
        $this->assertFalse($this->odbc->isConnected());

    }

    public function testCanTellIfIsConnectedOrNot()
    {
        $this->assertTrue($this->odbc->isConnected());
        $this->assertTrue($this->odbc->disconnect());
        $this->assertFalse($this->odbc->isConnected());
    }

    public function testCanGetListOfTables()
    {
        $tables = $this->odbc->getTables();

        $this->assertCount(1, $tables);
        $this->assertEquals('products', $tables[0]['TABLENAME']);
    }

    public function testCanGetTableNames()
    {
        $tableNames = $this->odbc->getTableNames();

        $this->assertCount(1, $tableNames);
        $this->assertEquals(['products'], $tableNames);
    }

}