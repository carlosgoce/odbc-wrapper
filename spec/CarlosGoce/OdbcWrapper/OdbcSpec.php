<?php

namespace spec\CarlosGoce\OdbcWrapper;

use CarlosGoce\OdbcWrapper\Exception\FetchModeDoesNotExistsException;
use CarlosGoce\OdbcWrapper\Odbc;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class OdbcSpec
 * @package spec\CarlosGoce\OdbcWrapper
 * @mixin Odbc
 *
 * @todo Using a real connection. Looking how to mock the php functions
 */
class OdbcSpec extends ObjectBehavior
{
    protected $connectionName = 'vfp86';

    function it_is_initializable()
    {
        $this->shouldHaveType('CarlosGoce\OdbcWrapper\Odbc');
    }

    function it_can_connect_to_a_given_odbc_dsn()
    {
        $this->connect($this->connectionName);
    }

    function it_can_disconnect_the_current_connection()
    {
        $this->disconnect()->shouldReturn(false);

        $this->connect($this->connectionName);
        $this->disconnect()->shouldReturn(true);

        $this->disconnect()->shouldReturn(false);
    }

    function it_can_tell_if_is_connected_or_not()
    {
        $this->isConnected()->shouldReturn(false);

        $this->connect($this->connectionName);

        $this->isConnected()->shouldReturn(true);
    }

    function it_let_change_fetch_mode()
    {
        $this->getFetchMode()->shouldReturn(Odbc::FETCH_AS_ARRAY);

        $this->setFetchMode(Odbc::FETCH_AS_OBJECT);
        $this->getFetchMode()->shouldReturn(Odbc::FETCH_AS_OBJECT);

        try {
            $this->setFetchMode(9999);
        }
        catch(FetchModeDoesNotExistsException $e) {

        }
    }

    function it_can_return_the_list_of_tables_of_the_connection()
    {
        $this->connect($this->connectionName);

        $tables = $this->getTables()->getWrappedObject();

        expect(is_array($tables));
    }

    function it_performs_queries()
    {
        $this->connect($this->connectionName);
        $result = $this->query("SELECT * FROM codpais")->getWrappedObject();

        expect(is_array($result));
    }
}
