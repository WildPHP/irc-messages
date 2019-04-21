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
use WildPHP\Messages\Raw;

class IrcMessageTest extends TestCase
{
    public function testMessageParameters(): void
    {
        $raw = @new Raw('test');

        $raw->setTags(['test']);

        self::assertEquals(['test'], $raw->getTags());
    }
}
