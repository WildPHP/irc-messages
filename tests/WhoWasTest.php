<?php
/**
 * Created by PhpStorm.
 * User: rkerkhof
 * Date: 14/03/2019
 * Time: 15:55
 */

namespace WildPHP\Tests;

use WildPHP\Messages\WhoWas;
use PHPUnit\Framework\TestCase;

class WhoWasTest extends TestCase
{
    public function test__toString()
    {
        $whowas = new WhoWas(['nickname1', 'nickname2'], 2, 'server');
        $this->assertEquals(['nickname1', 'nickname2'], $whowas->getNicknames());
        $this->assertEquals(2, $whowas->getCount());
        $this->assertEquals('server', $whowas->getServer());

        $expected = 'WHOWAS nickname1,nickname2 2 server';
        $this->assertEquals($expected, $whowas->__toString());

        $whowas = new WhoWas('nickname1', 2, 'server');
        $this->assertEquals(['nickname1'], $whowas->getNicknames());
    }
}
