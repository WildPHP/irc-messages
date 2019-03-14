<?php
/**
 * Created by PhpStorm.
 * User: rkerkhof
 * Date: 14/03/2019
 * Time: 15:55
 */

namespace WildPHP\Tests;

use WildPHP\Messages\WhoIs;
use PHPUnit\Framework\TestCase;

class WhoIsTest extends TestCase
{
    public function test__toString()
    {
        $whois = new WhoIs(['nickname1', 'nickname2'], 'server');
        $this->assertEquals(['nickname1', 'nickname2'], $whois->getNicknames());
        $this->assertEquals('server', $whois->getServer());

        $expected = 'WHOIS server nickname1,nickname2';
        $this->assertEquals($expected, $whois->__toString());

        $whois = new WhoIs('nickname1', 'server');
        $this->assertEquals(['nickname1'], $whois->getNicknames());
    }
}
