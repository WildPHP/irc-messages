# irc-messages
----------
[![Build Status](https://scrutinizer-ci.com/g/WildPHP/irc-messages/badges/build.png)](https://scrutinizer-ci.com/g/WildPHP/irc-messages/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/WildPHP/irc-messages/badges/quality-score.png)](https://scrutinizer-ci.com/g/WildPHP/irc-messages/?branch=master)
[![Scrutinizer Code Coverage](https://scrutinizer-ci.com/g/WildPHP/irc-messages/badges/coverage.png)](https://scrutinizer-ci.com/g/WildPHP/irc-messages/code-structure/master/code-coverage)
[![Latest Stable Version](https://poser.pugx.org/wildphp/irc-messages/v/stable)](https://packagist.org/packages/wildphp/irc-messages)
[![Latest Unstable Version](https://poser.pugx.org/wildphp/irc-messages/v/unstable)](https://packagist.org/packages/wildphp/irc-messages)
[![Total Downloads](https://poser.pugx.org/wildphp/irc-messages/downloads)](https://packagist.org/packages/wildphp/irc-messages)


Implementation of various IRC messages designed for WildPHP.

## Installation
To install this package, you need [Composer](https://getcomposer.org/).

    $ composer require wildphp/irc-messages ^0.1
    
## Usage
These messages can be used standalone without usage of any other utilities. For example:

```php
// Privmsg(string $channel, string $message)
$privmsg = new Privmsg('#channel', 'This is a message');

$rawMessage = (string) $privmsg; //: "PRIVMSG #channel :This is a message" + "\r\n" 
```

`IncomingMessage` instances may be created from parsed messages, which is useful if you just want a generic
representation of the message. These can be casted into specialized objects if the message type implements `IncomingMessageInterface`.

```php
// IncomingMessage(string $prefix, string $verb, array $args)
$incoming = new IncomingMessage('nickname!username@hostname', 'PRIVMSG', ['This is a message']);

$privmsg = Privmsg::fromIncomingMessage($incoming);
```

A utility class is provided which tries to find the most appropriate class to specialize messages to.

```php
$incoming = new IncomingMessage('nickname!username@hostname', 'PRIVMSG', ['This is a message']);

$privmsg = MessageCaster::castMessage($incoming);

// $privmsg is now an object of the Privmsg class.
```

Numeric messages are stored in the RPL directory under their official name, because PHP does not support numeric class names.
You can use the `RplTranslateEnum` class to translate numeric verbs into the corresponding class names.
Make sure to pass the numerics as **strings** to account for leading zeroes. A numeric like '3' will not be accepted.

```php
$topicClass = RplTranslateEnum::translateNumeric('332');

// $topicClass is now '\WildPHP\Messages\RPL\Topic'
``` 
    
## Implemented messages
Messages can be implemented as incoming or outgoing messages.

An incoming message may be converted from an `IncomingMessage` class.

An outgoing message may be converted to a string in order to send it to an IRC server.

| Name              | As incoming? | As outgoing? | Extra's?
|-------------------|--------------|--------------|---------
| 001/RPL_WELCOME   |       x      |              |
| 005/RPL_ISUPPORT  |       x      |              |
| 332/RPL_TOPIC     |       x      |              |
| 353/RPL_NAMREPLY  |       x      |              |
| 354/RPL_WHOSPCRPL |       x      |              |
| 366/RPL_ENDOFNAME |       x      |              |
| ACCOUNT           |       x      |              |
| AUTHENTICATE      |       x      |       x      |
| AWAY              |       x      |       x      |
| CAP               |       x      |       x      |
| ERROR             |       x      |              |
| JOIN              |       x      |       x      | extended-join support for incoming messages
| KICK              |       x      |       x      |
| MODE              |       x      |       x      |
| NAMES             |              |       x      |
| NICK              |       x      |       x      |
| NOTICE            |       x      |       x      |
| PART              |       x      |       x      |
| PASS              |              |       x      |
| PING              |       x      |       x      |
| PONG              |       x      |       x      |
| PRIVMSG           |       x      |       x      |
| QUIT              |       x      |       x      |
| REMOVE            |              |       x      |
| TOPIC             |       x      |       x      |
| USER              |       x      |       x      |
| VERSION           |              |       x      |
| WHO               |       x      |       x      |
| WHOIS             |              |       x      |
| WHOWAS            |              |       x      |

## Contributors
You can see the full list of contributors [in the GitHub repository](https://github.com/WildPHP/irc-messages/graphs/contributors).
