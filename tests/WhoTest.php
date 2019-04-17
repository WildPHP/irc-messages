<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Tests;

use InvalidArgumentException;
use WildPHP\Messages\Generics\IrcMessage;
use WildPHP\Messages\Generics\Prefix;
use WildPHP\Messages\Who;
use PHPUnit\Framework\TestCase;

class WhoTest extends TestCase
{
    public function test__toString(): void
    {
        $who = new Who('#someChannel', '%nuhaf');

        $this->assertEquals('#someChannel', $who->getChannel());
        $this->assertEquals('%nuhaf', $who->getOptions());

        $expected = 'WHO #someChannel %nuhaf' . "\r\n";
        $this->assertEquals($expected, $who->__toString());
    }

    public function testFromIncomingMessage(): void
    {
        $prefix = 'nickname!username@hostname';
        $verb = 'WHO';
        $args = ['#someChannel', '%nuhaf'];
        $incoming = new IrcMessage($prefix, $verb, $args);

        $who = Who::fromIncomingMessage($incoming);

        $userPrefix = new Prefix('nickname', 'username', 'hostname');
        $this->assertEquals($userPrefix, $who->getPrefix());
        $this->assertEquals('#someChannel', $who->getChannel());
        $this->assertEquals('%nuhaf', $who->getOptions());
    }

    public function testFromIncomingMessageThrowsException(): void
    {
        $prefix = 'server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incoming = new IrcMessage($prefix, $verb, $args);

        $this->expectException(InvalidArgumentException::class);
        Who::fromIncomingMessage($incoming);
    }
}
