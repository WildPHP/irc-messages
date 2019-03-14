<?php
/**
 * Created by PhpStorm.
 * User: rkerkhof
 * Date: 14/03/2019
 * Time: 15:47
 */

namespace WildPHP\Tests;

use WildPHP\Messages\Remove;
use PHPUnit\Framework\TestCase;

class RemoveTest extends TestCase
{
    public function test__toString()
    {
        $remove = new Remove('#channel', 'nickname', 'Get out!');

        $this->assertEquals('#channel', $remove->getChannel());
        $this->assertEquals('nickname', $remove->getTarget());
        $this->assertEquals('Get out!', $remove->getMessage());

        $expected = 'REMOVE #channel nickname :Get out!' . "\r\n";
        $this->assertEquals($expected, $remove->__toString());
    }
}
