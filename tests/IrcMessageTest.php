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
use WildPHP\Messages\Who;
use WildPHP\Messages\WhoIs;
use WildPHP\Messages\WhoWas;

class IrcMessageTest extends TestCase
{
	public function testAccountCreate()
	{
		$account = new Account('ircAccount');

		$this->assertEquals('ircAccount', $account->getAccountName());

		$prefix = ':server';
		$verb = 'TEEHEE';
		$args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
		$this->expectException(\InvalidArgumentException::class);
		Account::fromIncomingMessage($incomingIrcMessage);
	}

	public function testAccountReceive()
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

	public function testAuthenticateCreate()
	{
		$authenticate = new Authenticate('+');

		$this->assertEquals('+', $authenticate->getResponse());

		$expected = 'Authenticate +' . "\r\n";
		$this->assertEquals($expected, $authenticate->__toString());
	}

	public function testAuthenticateReceive()
	{
        $prefix = '';
        $verb = 'AUTHENTICATE';
        $args = ['+'];
        $incoming = new IrcMessage($prefix, $verb, $args);
		$authenticate = Authenticate::fromIncomingMessage($incoming);

		$this->assertEquals('+', $authenticate->getResponse());

		$prefix = ':server';
		$verb = 'TEEHEE';
		$args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
		$this->expectException(\InvalidArgumentException::class);
		Authenticate::fromIncomingMessage($incomingIrcMessage);
	}

	public function testAwayCreate()
	{
		$away = new Away('A sample message');

		$this->assertEquals('A sample message', $away->getMessage());

		$expected = 'Away :A sample message' . "\r\n";
		$this->assertEquals($expected, $away->__toString());
	}

	public function testAwayReceive()
	{
        $prefix = 'nickname!username@hostname';
        $verb = 'AWAY';
        $args = ['A sample message'];
        $incoming = new IrcMessage($prefix, $verb, $args);
		$away = Away::fromIncomingMessage($incoming);

		$this->assertEquals('nickname', $away->getNickname());
		$this->assertEquals('A sample message', $away->getMessage());

		$prefix = ':server';
		$verb = 'TEEHEE';
		$args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
		$this->expectException(\InvalidArgumentException::class);
		Away::fromIncomingMessage($incomingIrcMessage);
	}

	public function testCapCreate()
    {
        $cap = new Cap('REQ', ['cap1', 'cap2']);

        $this->assertEquals('REQ', $cap->getCommand());
        $this->assertEquals(['cap1', 'cap2'], $cap->getCapabilities());

        $expected = 'CAP REQ :cap1 cap2' . "\r\n";
        $this->assertEquals($expected, $cap->__toString());
    }

	public function testCapCreateInvalidSubcommand()
	{
		$this->expectException(\InvalidArgumentException::class);
		
		new Cap('INVALID');
    }

    public function testCapReceive()
    {
        $prefix = 'server';
        $verb = 'CAP';
        $args = ['*', 'LS', 'cap1 cap2'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $cap = Cap::fromIncomingMessage($incoming);

        $this->assertEquals('LS', $cap->getCommand());
        $this->assertEquals(['cap1', 'cap2'], $cap->getCapabilities());
        $this->assertEquals('*', $cap->getClientIdentifier());

	    $prefix = ':server';
		$verb = 'TEEHEE';
		$args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
	    $this->expectException(\InvalidArgumentException::class);
	    Cap::fromIncomingMessage($incomingIrcMessage);
    }

	public function testErrorReceive()
	{
        $prefix = '';
        $verb = 'ERROR';
        $args = ['A sample message'];
        $incoming = new IrcMessage($prefix, $verb, $args);
		$error = Error::fromIncomingMessage($incoming);

		$this->assertEquals('A sample message', $error->getMessage());

		$prefix = ':server';
		$verb = 'TEEHEE';
		$args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
		$this->expectException(\InvalidArgumentException::class);
		Error::fromIncomingMessage($incomingIrcMessage);
	}

	public function testJoinCreate()
	{
		$join = new Join(['#channel1', '#channel2'], ['key1', 'key2']);

		$this->assertEquals(['#channel1', '#channel2'], $join->getChannels());
		$this->assertEquals(['key1', 'key2'], $join->getKeys());

		$expected = 'Join #channel1,#channel2 key1,key2' . "\r\n";
		$this->assertEquals($expected, $join->__toString());

		$join = new Join('#channel1', 'key1');

		$this->assertEquals(['#channel1'], $join->getChannels());
		$this->assertEquals(['key1'], $join->getKeys());
	}

	public function testJoinCreateKeyMismatch()
	{
		$this->expectException(InvalidArgumentException::class);

		new Join(['#channel1', '#channel2'], ['key1']);
	}

	public function testJoinReceiveExtended()
	{
		$prefix = 'nickname!username@hostname';
        $verb = 'JOIN';
        $args = ['#channel', 'ircAccountName', 'realname'];
        $incoming = new IrcMessage($prefix, $verb, $args);
		$join = Join::fromIncomingMessage($incoming);

		$this->assertEquals('nickname', $join->getNickname());
		$this->assertEquals(['#channel'], $join->getChannels());
		$this->assertEquals('ircAccountName', $join->getIrcAccount());
		$this->assertEquals('realname', $join->getRealname());
	}

	public function testJoinReceiveRegular()
	{
        $prefix = 'nickname!username@hostname';
        $verb = 'JOIN';
        $args = ['#channel'];
        $incoming = new IrcMessage($prefix, $verb, $args);
		$join = Join::fromIncomingMessage($incoming);

		$this->assertEquals('nickname', $join->getNickname());
		$this->assertEquals(['#channel'], $join->getChannels());
		$this->assertEquals('', $join->getIrcAccount());
		$this->assertEquals('', $join->getRealname());

		$prefix = ':server';
		$verb = 'TEEHEE';
		$args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
		$this->expectException(\InvalidArgumentException::class);
		Join::fromIncomingMessage($incomingIrcMessage);
	}

	public function testKickCreate()
	{
		$kick = new Kick('#channel', 'nickname', 'Bleep you!');

		$this->assertEquals('#channel', $kick->getChannel());
		$this->assertEquals('nickname', $kick->getTarget());
		$this->assertEquals('Bleep you!', $kick->getMessage());

		$expected = 'KICK #channel nickname :Bleep you!' . "\r\n";
		$this->assertEquals($expected, $kick->__toString());
	}

	public function testKickReceive()
	{
        $prefix = 'nickname!username@hostname';
        $verb = 'KICK';
        $args = ['#somechannel', 'othernickname', 'You deserved it!'];
        $incoming = new IrcMessage($prefix, $verb, $args);
		$kick = Kick::fromIncomingMessage($incoming);

		$userPrefix = new Prefix('nickname', 'username', 'hostname');
		$this->assertEquals($userPrefix, $kick->getPrefix());
		$this->assertEquals('nickname', $kick->getNickname());
		$this->assertEquals('othernickname', $kick->getTarget());
		$this->assertEquals('#somechannel', $kick->getChannel());
		$this->assertEquals('You deserved it!', $kick->getMessage());

		$prefix = ':server';
		$verb = 'TEEHEE';
		$args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
		$this->expectException(\InvalidArgumentException::class);
		Kick::fromIncomingMessage($incomingIrcMessage);
	}

	public function testModeCreate()
	{
		$mode = new Mode('target', '-o+b', ['arg1', 'arg2']);

		$this->assertEquals('target', $mode->getTarget());
		$this->assertEquals('-o+b', $mode->getFlags());
		$this->assertEquals(['arg1', 'arg2'], $mode->getArguments());

		$expected = 'MODE target -o+b arg1 arg2' . "\r\n";
		$this->assertEquals($expected, $mode->__toString());
	}

	public function testModeReceiveChannel()
	{
        $prefix = 'nickname!username@hostname';
        $verb = 'MODE';
        $args = ['#channel', '-o+b', 'arg1', 'arg2'];
        $incoming = new IrcMessage($prefix, $verb, $args);
		$mode = Mode::fromIncomingMessage($incoming);

		$userPrefix = new Prefix('nickname', 'username', 'hostname');
		$this->assertEquals($userPrefix, $mode->getPrefix());
		$this->assertEquals('#channel', $mode->getTarget());
		$this->assertEquals('nickname', $mode->getNickname());
		$this->assertEquals('-o+b', $mode->getFlags());
		$this->assertEquals(['arg1', 'arg2'], $mode->getArguments());

		$prefix = ':server';
		$verb = 'TEEHEE';
		$args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
		$this->expectException(\InvalidArgumentException::class);
		Mode::fromIncomingMessage($incomingIrcMessage);
	}

	public function testModeReceiveUser()
	{
        $prefix = 'nickname!username@hostname';
        $verb = 'MODE';
        $args = ['user', '-o+b'];
        $incoming = new IrcMessage($prefix, $verb, $args);
		$mode = Mode::fromIncomingMessage($incoming);

		$userPrefix = new Prefix('nickname', 'username', 'hostname');
		$this->assertEquals($userPrefix, $mode->getPrefix());
		$this->assertEquals('user', $mode->getTarget());
		$this->assertEquals('nickname', $mode->getNickname());
		$this->assertEquals('-o+b', $mode->getFlags());
		$this->assertEquals([], $mode->getArguments());
	}

	public function testModeReceiveInitial()
	{
        $prefix = 'nickname!username@hostname';
        $verb = 'MODE';
        $args = ['nickname', '-o+b'];
        $incoming = new IrcMessage($prefix, $verb, $args);
		$mode = Mode::fromIncomingMessage($incoming);

		$this->assertEquals('nickname', $mode->getTarget());
		$this->assertEquals('nickname', $mode->getNickname());
		$this->assertEquals('-o+b', $mode->getFlags());
		$this->assertEquals([], $mode->getArguments());
	}

	public function testNamesCreate()
	{
		$names = new Names('#testChannel', 'testServer');

		$this->assertEquals(['#testChannel'], $names->getChannels());
		$this->assertEquals('testServer', $names->getServer());

		$expected = 'NAMES #testChannel testServer';
		$this->assertEquals($expected, $names->__toString());
	}

	public function testNickCreate()
	{
		$nick = new Nick('newnickname');

		$this->assertEquals('newnickname', $nick->getNewNickname());

		$expected = 'NICK newnickname' . "\r\n";
		$this->assertEquals($expected, $nick->__toString());
	}

	public function testNickReceive()
	{
        $prefix = 'nickname!username@hostname';
        $verb = 'NICK';
        $args = ['newnickname'];
        $incoming = new IrcMessage($prefix, $verb, $args);
		$nick = Nick::fromIncomingMessage($incoming);

		$userPrefix = new Prefix('nickname', 'username', 'hostname');
		$this->assertEquals($userPrefix, $nick->getPrefix());
		$this->assertEquals('nickname', $nick->getNickname());
		$this->assertEquals('newnickname', $nick->getNewNickname());

		$prefix = ':server';
		$verb = 'TEEHEE';
		$args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
		$this->expectException(\InvalidArgumentException::class);
		Nick::fromIncomingMessage($incomingIrcMessage);
	}

	public function testNoticeCreate()
	{
		$notice = new Notice('#somechannel', 'This is a test message');

		$this->assertEquals('#somechannel', $notice->getChannel());
		$this->assertEquals('This is a test message', $notice->getMessage());

		$expected = 'NOTICE #somechannel :This is a test message' . "\r\n";
		$this->assertEquals($expected, $notice->__toString());
	}

	public function testNoticeReceive()
	{
        $prefix = 'nickname!username@hostname';
        $verb = 'NOTICE';
        $args = ['#somechannel', 'This is a test message'];
        $incoming = new IrcMessage($prefix, $verb, $args);
		$notice = Notice::fromIncomingMessage($incoming);

		$userPrefix = new Prefix('nickname', 'username', 'hostname');
		$this->assertEquals($userPrefix, $notice->getPrefix());
		$this->assertEquals('#somechannel', $notice->getChannel());
		$this->assertEquals('This is a test message', $notice->getMessage());

		$prefix = ':server';
		$verb = 'TEEHEE';
		$args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
		$this->expectException(\InvalidArgumentException::class);
		Notice::fromIncomingMessage($incomingIrcMessage);
	}

	public function testPartCreate()
	{
		$part = new Part(['#channel1', '#channel2'], 'I am out');

		$this->assertEquals(['#channel1', '#channel2'], $part->getChannels());
		$this->assertEquals('I am out', $part->getMessage());

		$expected = 'PART #channel1,#channel2 :I am out' . "\r\n";
		$this->assertEquals($expected, $part->__toString());
	}

	public function testPartReceive()
	{
        $prefix = 'nickname!username@hostname';
        $verb = 'PART';
        $args = ['#channel', 'I have a valid reason'];
        $incoming = new IrcMessage($prefix, $verb, $args);
		$part = Part::fromIncomingMessage($incoming);

		$userPrefix = new Prefix('nickname', 'username', 'hostname');
		$this->assertEquals($userPrefix, $part->getPrefix());
		$this->assertEquals('nickname', $part->getNickname());
		$this->assertEquals(['#channel'], $part->getChannels());
		$this->assertEquals('I have a valid reason', $part->getMessage());

		$prefix = ':server';
		$verb = 'TEEHEE';
		$args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
		$this->expectException(\InvalidArgumentException::class);
		Part::fromIncomingMessage($incomingIrcMessage);
	}

	public function testPassCreate()
    {
        $pass = new Pass('myseekritpassw0rd');

        $this->assertEquals('myseekritpassw0rd', $pass->getPassword());

        $expected = 'PASS :myseekritpassw0rd' . "\r\n";
        $this->assertEquals($expected, $pass->__toString());
    }

	public function testPingCreate()
	{
		$ping = new Ping('testserver1', 'testserver2');

		$this->assertEquals('testserver1', $ping->getServer1());
		$this->assertEquals('testserver2', $ping->getServer2());

		$expected = 'PING testserver1 testserver2' . "\r\n";
		$this->assertEquals($expected, $ping->__toString());
	}

	public function testPingReceive()
	{
        $prefix = '';
        $verb = 'PING';
        $args = ['testserver1', 'testserver2'];
        $incoming = new IrcMessage($prefix, $verb, $args);
		$ping = Ping::fromIncomingMessage($incoming);

		$this->assertEquals('testserver1', $ping->getServer1());
		$this->assertEquals('testserver2', $ping->getServer2());

		$prefix = ':server';
		$verb = 'TEEHEE';
		$args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
		$this->expectException(\InvalidArgumentException::class);
		Ping::fromIncomingMessage($incomingIrcMessage);
	}

	public function testPongCreate()
	{
		$pong = new Pong('testserver1', 'testserver2');

		$this->assertEquals('testserver1', $pong->getServer1());
		$this->assertEquals('testserver2', $pong->getServer2());

		$expected = 'PONG testserver1 testserver2' . "\r\n";
		$this->assertEquals($expected, $pong->__toString());
	}

	public function testPongReceive()
	{
        $prefix = '';
        $verb = 'PONG';
        $args = ['testserver1', 'testserver2'];
        $incoming = new IrcMessage($prefix, $verb, $args);
		$pong = Pong::fromIncomingMessage($incoming);

		$this->assertEquals('testserver1', $pong->getServer1());
		$this->assertEquals('testserver2', $pong->getServer2());

		$prefix = ':server';
		$verb = 'TEEHEE';
		$args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
		$this->expectException(\InvalidArgumentException::class);
		Pong::fromIncomingMessage($incomingIrcMessage);
	}

	public function testPrivmsgCreate()
	{
		$privmsg = new Privmsg('#somechannel', 'This is a test message');

		$this->assertEquals('#somechannel', $privmsg->getChannel());
		$this->assertEquals('This is a test message', $privmsg->getMessage());

		$expected = 'PRIVMSG #somechannel :This is a test message' . "\r\n";
		$this->assertEquals($expected, $privmsg->__toString());
	}

	public function testPrivmsgCreateCTCP()
	{
		$privmsg = new Privmsg('#somechannel', 'This is a test message');
		$privmsg->setCtcpVerb('ACTION');
		$privmsg->setIsCtcp(true);

		$this->assertEquals('#somechannel', $privmsg->getChannel());
		$this->assertEquals('This is a test message', $privmsg->getMessage());
		$this->assertEquals('ACTION', $privmsg->getCtcpVerb());
		$this->assertTrue($privmsg->isCtcp());

		$expected = 'PRIVMSG #somechannel :' . "\x01" . 'ACTION This is a test message' . "\x01\r\n";
		$this->assertEquals($expected, $privmsg->__toString());
	}

	public function testPrivmsgReceive()
	{
        $prefix = 'nickname!username@hostname';
        $verb = 'PRIVMSG';
        $args = ['#somechannel', 'This is a test message'];
        $incoming = new IrcMessage($prefix, $verb, $args);
		$privmsg = Privmsg::fromIncomingMessage($incoming);

		$userPrefix = new Prefix('nickname', 'username', 'hostname');
		$this->assertEquals($userPrefix, $privmsg->getPrefix());
		$this->assertEquals('#somechannel', $privmsg->getChannel());
		$this->assertEquals('This is a test message', $privmsg->getMessage());

		$prefix = ':server';
		$verb = 'TEEHEE';
		$args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
		$this->expectException(\InvalidArgumentException::class);
		Privmsg::fromIncomingMessage($incomingIrcMessage);
	}

	public function testPrivmsgReceiveCTCP()
	{
        $prefix = 'nickname!username@hostname';
        $verb = 'PRIVMSG';
        $args = ['#somechannel', "\x01" . 'ACTION This is a test message' . "\x01"];
        $incoming = new IrcMessage($prefix, $verb, $args);
		$privmsg = Privmsg::fromIncomingMessage($incoming);

		$userPrefix = new Prefix('nickname', 'username', 'hostname');
		$this->assertEquals($userPrefix, $privmsg->getPrefix());
		$this->assertEquals('#somechannel', $privmsg->getChannel());
		$this->assertTrue($privmsg->isCtcp());
		$this->assertEquals('ACTION', $privmsg->getCtcpVerb());
		$this->assertEquals('This is a test message', $privmsg->getMessage());
	}

	public function testQuitCreate()
	{
		$quit = new Quit('A sample message');

		$this->assertEquals('A sample message', $quit->getMessage());

		$expected = 'QUIT :A sample message' . "\r\n";
		$this->assertEquals($expected, $quit->__toString());
	}

	public function testQuitReceive()
	{
        $prefix = 'nickname!username@hostname';
        $verb = 'QUIT';
        $args = ['A sample message'];
        $incoming = new IrcMessage($prefix, $verb, $args);
		$quit = Quit::fromIncomingMessage($incoming);

		$userPrefix = new Prefix('nickname', 'username', 'hostname');
		$this->assertEquals($userPrefix, $quit->getPrefix());
		$this->assertEquals('nickname', $quit->getNickname());
		$this->assertEquals('A sample message', $quit->getMessage());

		$prefix = ':server';
		$verb = 'TEEHEE';
		$args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
		$this->expectException(\InvalidArgumentException::class);
		Quit::fromIncomingMessage($incomingIrcMessage);
	}

	public function testRawCreate()
    {
        $raw = new Raw('a command');

        $this->assertEquals('a command', $raw->getCommand());

        $expected = 'a command' . "\r\n";
        $this->assertEquals($expected, $raw->__toString());
    }

	public function testRemoveCreate()
	{
		$remove = new \WildPHP\Messages\Remove('#channel', 'nickname', 'Get out!');
		
		$this->assertEquals('#channel', $remove->getChannel());
		$this->assertEquals('nickname', $remove->getTarget());
		$this->assertEquals('Get out!', $remove->getMessage());
		
		$expected = 'REMOVE #channel nickname :Get out!' . "\r\n";
		$this->assertEquals($expected, $remove->__toString());
    }

	public function testRplEndOfNamesReceive()
	{
        $prefix = 'server';
        $verb = '366';
        $args = ['nickname', '#channel', 'End of /NAMES list.'];
        $incoming = new IrcMessage($prefix, $verb, $args);
		$rpl_endofnames = EndOfNames::fromIncomingMessage($incoming);

		$this->assertEquals('nickname', $rpl_endofnames->getNickname());
		$this->assertEquals('#channel', $rpl_endofnames->getChannel());
		$this->assertEquals('End of /NAMES list.', $rpl_endofnames->getMessage());

		$prefix = ':server';
		$verb = 'TEEHEE';
		$args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
		$this->expectException(\InvalidArgumentException::class);
		EndOfNames::fromIncomingMessage($incomingIrcMessage);
    }

	public function testRplIsupportReceive()
	{
        $prefix = 'server';
        $verb = '005';
        $args = ['nickname', 'KEY1=value', 'KEY2=value2', 'are supported by this server'];
        $incoming = new IrcMessage($prefix, $verb, $args);
		$rpl_isupport = ISupport::fromIncomingMessage($incoming);

		$this->assertEquals(['key1' => 'value', 'key2' => 'value2'], $rpl_isupport->getVariables());
		$this->assertEquals('server', $rpl_isupport->getServer());
		$this->assertEquals('nickname', $rpl_isupport->getNickname());
		$this->assertEquals('are supported by this server', $rpl_isupport->getMessage());

		$prefix = ':server';
		$verb = 'TEEHEE';
		$args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
		$this->expectException(\InvalidArgumentException::class);
		ISupport::fromIncomingMessage($incomingIrcMessage);
    }

	public function testRplNamReplyReceive()
	{
        $prefix = 'server';
        $verb = '353';
        $args = ['nickname', '+', '#channel', 'nickname1 nickname2 nickname3'];
        $incoming = new IrcMessage($prefix, $verb, $args);
		$rpl_namreply = NamReply::fromIncomingMessage($incoming);

		$this->assertEquals('server', $rpl_namreply->getServer());
		$this->assertEquals('nickname', $rpl_namreply->getNickname());
		$this->assertEquals('+', $rpl_namreply->getVisibility());
		$this->assertEquals(['nickname1', 'nickname2', 'nickname3'], $rpl_namreply->getNicknames());

		$prefix = ':server';
		$verb = 'TEEHEE';
		$args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
		$this->expectException(\InvalidArgumentException::class);
		NamReply::fromIncomingMessage($incomingIrcMessage);
    }

	public function testRplTopicReceive()
	{
        $prefix = 'server';
        $verb = '332';
        $args = ['nickname', '#channel', 'A new topic message'];
        $incoming = new IrcMessage($prefix, $verb, $args);
		$rpl_topic = RplTopic::fromIncomingMessage($incoming);

		$this->assertEquals('server', $rpl_topic->getServer());
		$this->assertEquals('nickname', $rpl_topic->getNickname());
		$this->assertEquals('#channel', $rpl_topic->getChannel());
		$this->assertEquals('A new topic message', $rpl_topic->getMessage());

		$prefix = ':server';
		$verb = 'TEEHEE';
		$args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
		$this->expectException(\InvalidArgumentException::class);
		RplTopic::fromIncomingMessage($incomingIrcMessage);
    }

	public function testRplWelcomeReceive()
	{
        $prefix = 'server';
        $verb = '001';
        $args = ['nickname', 'Welcome to server!'];
        $incoming = new IrcMessage($prefix, $verb, $args);
		$rpl_welcome = Welcome::fromIncomingMessage($incoming);

		$this->assertEquals('server', $rpl_welcome->getServer());
		$this->assertEquals('nickname', $rpl_welcome->getNickname());
		$this->assertEquals('Welcome to server!', $rpl_welcome->getMessage());

		$prefix = ':server';
		$verb = 'TEEHEE';
		$args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
		$this->expectException(\InvalidArgumentException::class);
		Welcome::fromIncomingMessage($incomingIrcMessage);
    }

	public function testRplWhosPCRplReceive()
	{
        $prefix = 'server';
        $verb = '354';
        $args = ['ownnickname', 'username', 'hostname', 'nickname', 'status', 'accountname'];
        $incoming = new IrcMessage($prefix, $verb, $args);
		$rpl_whospcrpl = WhosPcRpl::fromIncomingMessage($incoming);

		$this->assertEquals('server', $rpl_whospcrpl->getServer());
		$this->assertEquals('ownnickname', $rpl_whospcrpl->getOwnNickname());
		$this->assertEquals('username', $rpl_whospcrpl->getUsername());
		$this->assertEquals('hostname', $rpl_whospcrpl->getHostname());
		$this->assertEquals('nickname', $rpl_whospcrpl->getNickname());
		$this->assertEquals('status', $rpl_whospcrpl->getStatus());
		$this->assertEquals('accountname', $rpl_whospcrpl->getAccountname());

		$prefix = ':server';
		$verb = 'TEEHEE';
		$args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
		$this->expectException(\InvalidArgumentException::class);
		WhosPcRpl::fromIncomingMessage($incomingIrcMessage);
    }

	public function testTopicCreate()
    {
        $topic = new TOPIC('#someChannel', 'Test message');

        $this->assertEquals('#someChannel', $topic->getChannel());
        $this->assertEquals('Test message', $topic->getMessage());

        $expected = 'TOPIC #someChannel :Test message' . "\r\n";
        $this->assertEquals($expected, $topic->__toString());
    }

    public function testTopicReceive()
    {
        $prefix = 'nickname!username@hostname';
        $verb = 'TOPIC';
        $args = ['#someChannel', 'This is a new topic'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $topic = Topic::fromIncomingMessage($incoming);

	    $userPrefix = new Prefix('nickname', 'username', 'hostname');
	    $this->assertEquals($userPrefix, $topic->getPrefix());
        $this->assertEquals('#someChannel', $topic->getChannel());
        $this->assertEquals('This is a new topic', $topic->getMessage());

	    $prefix = ':server';
		$verb = 'TEEHEE';
		$args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
	    $this->expectException(\InvalidArgumentException::class);
	    Topic::fromIncomingMessage($incomingIrcMessage);
    }

    public function testUserCreate()
    {
        $user = new User('myusername', 'localhost', 'someserver', 'arealname');

        $this->assertEquals('myusername', $user->getUsername());
        $this->assertEquals('localhost', $user->getHostname());
        $this->assertEquals('someserver', $user->getServername());
        $this->assertEquals('arealname', $user->getRealname());

        $expected = 'USER myusername localhost someserver :arealname' . "\r\n";
        $this->assertEquals($expected, $user->__toString());
    }

    public function testUserReceive()
    {
        $prefix = '';
        $verb = 'USER';
        $args = ['myusername', 'localhost', 'someserver', 'A real name'];
        $incoming = new IrcMessage($prefix, $verb, $args);
        $user = User::fromIncomingMessage($incoming);

        $this->assertEquals('myusername', $user->getUsername());
        $this->assertEquals('localhost', $user->getHostname());
        $this->assertEquals('someserver', $user->getServername());
        $this->assertEquals('A real name', $user->getRealname());

	    $prefix = ':server';
		$verb = 'TEEHEE';
		$args = ['argument'];
        $incomingIrcMessage = new IrcMessage($prefix, $verb, $args);
	    $this->expectException(\InvalidArgumentException::class);
	    User::fromIncomingMessage($incomingIrcMessage);
    }

	public function testVersionCreate()
	{
		$version = new Version('server');
		$this->assertEquals('server', $version->getServer());

		$expected = 'VERSION server';
		$this->assertEquals($expected, $version->__toString());

		$version = new Version();
		$expected = 'VERSION';
		$this->assertEquals($expected, $version->__toString());
    }

	public function testWhoCreate()
    {
        $who = new Who('#someChannel', '%nuhaf');

        $this->assertEquals('#someChannel', $who->getChannel());
        $this->assertEquals('%nuhaf', $who->getOptions());

        $expected = 'WHO #someChannel %nuhaf' . "\r\n";
        $this->assertEquals($expected, $who->__toString());
    }

    public function testWhoReceive()
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

        $prefix = 'server';
        $verb = 'TEEHEE';
        $args = ['argument'];
        $incoming = new IrcMessage($prefix, $verb, $args);

	    $this->expectException(\InvalidArgumentException::class);
	    Who::fromIncomingMessage($incoming);
    }

	public function testWhoisCreate()
	{
		$whois = new WhoIs(['nickname1', 'nickname2'], 'server');
		$this->assertEquals(['nickname1', 'nickname2'], $whois->getNicknames());
		$this->assertEquals('server', $whois->getServer());

		$expected = 'WHOIS server nickname1,nickname2';
		$this->assertEquals($expected, $whois->__toString());
		
		$whois = new WhoIs('nickname1', 'server');
		$this->assertEquals(['nickname1'], $whois->getNicknames());
    }

	public function testWhoWasCreate()
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

	public function testMessageParameters()
	{
		$raw = new Raw('test');
		
		$raw->setMessageParameters(['test']);
		
		self::assertEquals(['test'], $raw->getMessageParameters());
	}
}
