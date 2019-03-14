<?php
/**
 * Created by PhpStorm.
 * User: rkerkhof
 * Date: 14/03/2019
 * Time: 15:37
 */

namespace WildPHP\Tests;

use WildPHP\Messages\Names;
use PHPUnit\Framework\TestCase;

class NamesTest extends TestCase
{

    public function test__toString()
    {
        $names = new Names('#testChannel', 'testServer');

        $this->assertEquals(['#testChannel'], $names->getChannels());
        $this->assertEquals('testServer', $names->getServer());

        $expected = 'NAMES #testChannel testServer';
        $this->assertEquals($expected, $names->__toString());
    }
}
