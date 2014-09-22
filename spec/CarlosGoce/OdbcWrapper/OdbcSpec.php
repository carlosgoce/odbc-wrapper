<?php

namespace spec\CarlosGoce\OdbcWrapper;

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
    function it_is_initializable()
    {
        $this->shouldHaveType('CarlosGoce\OdbcWrapper\Odbc');
    }

    function it_can_connect_to_a_given_odbc_dsn()
    {
        $this->connect('vfp86');
    }

    function it_can_disconnect_the_current_connection()
    {
        $this->disconnect()->shouldReturn(false);

        $this->connect('vfp86');
        $this->disconnect()->shouldReturn(true);

        $this->disconnect()->shouldReturn(false);
    }

    function it_can_tell_if_is_connected_or_not()
    {
        $this->isConnected()->shouldReturn(false);

        $this->connect('vfp86');

        $this->isConnected()->shouldReturn(true);
    }
}
