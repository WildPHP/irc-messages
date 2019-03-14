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

use PHPUnit\Framework\TestCase;
use WildPHP\Messages\Account;
use WildPHP\Messages\Authenticate;
use WildPHP\Messages\Away;
use WildPHP\Messages\Cap;
use WildPHP\Messages\Error;
use WildPHP\Messages\Generics\IrcMessage;
use WildPHP\Messages\Generics\Prefix;
use WildPHP\Messages\Join;
use WildPHP\Messages\Kick;
use WildPHP\Messages\Mode;
use WildPHP\Messages\Names;
use WildPHP\Messages\Nick;
use WildPHP\Messages\Notice;
use WildPHP\Messages\Part;
use WildPHP\Messages\Pass;
use WildPHP\Messages\Ping;
use WildPHP\Messages\Pong;
use WildPHP\Messages\Privmsg;
use WildPHP\Messages\Quit;
use WildPHP\Messages\Raw;
use WildPHP\Messages\RPL\EndOfNames;
use WildPHP\Messages\RPL\ISupport;
use WildPHP\Messages\RPL\NamReply;
use WildPHP\Messages\RPL\Topic as RplTopic;
use WildPHP\Messages\RPL\Welcome;
use WildPHP\Messages\RPL\WhosPcRpl;
use WildPHP\Messages\Topic;
use WildPHP\Messages\User;
use WildPHP\Messages\Version;
use WildPHP\Messages\WebIrc;
use WildPHP\Messages\Who;
use WildPHP\Messages\WhoIs;
use WildPHP\Messages\WhoWas;

class IrcMessageTest extends TestCase
{
	public function testMessageParameters()
	{
		$raw = @new Raw('test');

		$raw->setTags(['test']);

		self::assertEquals(['test'], $raw->getTags());
	}
}
