<?php
/**
 * Created by PhpStorm.
 * User: rkerkhof
 * Date: 14/03/2019
 * Time: 15:44
 */

namespace WildPHP\Tests;

use WildPHP\Messages\Pass;
use PHPUnit\Framework\TestCase;

class PassTest extends TestCase
{
    public function test__toString(): void
    {
        $pass = new Pass('myseekritpassw0rd');

        $this->assertEquals('myseekritpassw0rd', $pass->getPassword());

        $expected = 'PASS :myseekritpassw0rd' . "\r\n";
        $this->assertEquals($expected, $pass->__toString());
    }
}
