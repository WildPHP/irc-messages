<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */
declare(strict_types=1);

namespace WildPHP\Tests;

use PHPUnit\Framework\TestCase;
use WildPHP\Messages\Version;

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
