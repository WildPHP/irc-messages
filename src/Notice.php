<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

declare(strict_types=1);

namespace WildPHP\Messages;

use InvalidArgumentException;
use WildPHP\Messages\Generics\BaseIRCMessageImplementation;
use WildPHP\Messages\Generics\Prefix;
use WildPHP\Messages\Interfaces\IncomingMessageInterface;
use WildPHP\Messages\Interfaces\IrcMessageInterface;
use WildPHP\Messages\Interfaces\OutgoingMessageInterface;
use WildPHP\Messages\Traits\ChannelTrait;
use WildPHP\Messages\Traits\MessageTrait;
use WildPHP\Messages\Traits\NicknameTrait;
use WildPHP\Messages\Traits\PrefixTrait;

/**
 * Class NOTICE
 * @package WildPHP\Messages
 *
 * Syntax: prefix NOTICE #channel :message
 */
class Notice extends BaseIRCMessageImplementation implements IncomingMessageInterface, OutgoingMessageInterface
{
    use PrefixTrait;
    use ChannelTrait;
    use NicknameTrait;
    use MessageTrait;

    /**
     * @var string
     */
    protected static $verb = 'NOTICE';

    /**
     * NOTICE constructor.
     *
     * @param string $channel
     * @param string $message
     */
    public function __construct(string $channel, string $message)
    {
        $this->setChannel($channel);
        $this->setMessage($message);
    }

    /**
     * @param IrcMessageInterface $incomingMessage
     *
     * @return self
     */
    public static function fromIncomingMessage(IrcMessageInterface $incomingMessage): self
    {
        if ($incomingMessage->getVerb() !== self::getVerb()) {
            throw new InvalidArgumentException(sprintf(
                'Expected incoming %s; got %s',
                self::getVerb(),
                $incomingMessage->getVerb()
            ));
        }

        $prefix = Prefix::fromIncomingMessage($incomingMessage);
        [$channel, $message] = $incomingMessage->getArgs();

        $object = new self($channel, $message);
        $object->setPrefix($prefix);
        $object->setNickname($prefix->getNickname());
        $object->setTags($incomingMessage->getTags());

        return $object;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'NOTICE ' . $this->getChannel() . ' :' . $this->getMessage() . "\r\n";
    }
}
