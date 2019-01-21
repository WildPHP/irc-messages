<?php
/**
 * WildPHP - an advanced and easily extensible IRC bot written in PHP
 * Copyright (C) 2017 WildPHP
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace WildPHP\Tests;

use WildPHP\Messages\Account;
use PHPUnit\Framework\TestCase;
use WildPHP\Messages\Generics\IrcMessage;
use WildPHP\Messages\Generics\Prefix;

class AccountTest extends TestCase
{

    public function testFromIncomingMessage()
    {
        $prefix = 'nickname!username@hostname';
        $verb = 'ACCOUNT';
        $args = ['ircAccount'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $account = Account::fromIncomingMessage($incoming);

        $userPrefix = new Prefix('nickname', 'username', 'hostname');
        $this->assertEquals($userPrefix, $account->getPrefix());
        $this->assertEquals('ircAccount', $account->getAccountName());
    }

    public function testFromIncomingIrcMessageThrowsException()
    {
        $prefix = ':server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
        $this->expectException(\InvalidArgumentException::class);
        Account::fromIncomingMessage($incomingIrcMessage);
    }

    public function testGetSetAccountName()
    {
        $account = new Account('ircAccount');

        $accountName = 'test';
        $account->setAccountName($accountName);
        $this->assertEquals($accountName, $account->getAccountName());
    }

    public function test__construct()
    {
        $account = new Account('ircAccount');
        $this->assertEquals('ircAccount', $account->getAccountName());
    }
}
