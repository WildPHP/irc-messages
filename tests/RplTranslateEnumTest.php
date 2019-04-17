<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Tests;

use PHPUnit\Framework\TestCase;
use WildPHP\Messages\RPL\Topic;
use WildPHP\Messages\Utility\RplTranslateEnum;

class RplTranslateEnumTest extends TestCase
{
    public function testTranslate(): void
    {
        $expected = Topic::class;

        $this->assertEquals($expected, RplTranslateEnum::translateNumeric('332'));
    }

    public function testTranslateNotFound(): void
    {
        $expected = false;

        $this->assertEquals($expected, RplTranslateEnum::translateNumeric('999'));
    }
}
