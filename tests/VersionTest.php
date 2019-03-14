<?php
/**
 * Created by PhpStorm.
 * User: rkerkhof
 * Date: 14/03/2019
 * Time: 15:53
 */

namespace WildPHP\Tests;

use WildPHP\Messages\Version;
use PHPUnit\Framework\TestCase;

class VersionTest extends TestCase
{
    public function test__toString(): void
    {
        $version = new Version('server');
        $this->assertEquals('server', $version->getServer());

        $expected = 'VERSION server';
        $this->assertEquals($expected, $version->__toString());

        $version = new Version();
        $expected = 'VERSION';
        $this->assertEquals($expected, $version->__toString());
    }
}