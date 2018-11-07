<?php
/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Messages;

use WildPHP\Messages\Generics\BaseIRCMessage;
use WildPHP\Messages\Generics\IncomingMessage;
use WildPHP\Messages\Interfaces\OutgoingMessageInterface;
use WildPHP\Messages\Generics\Prefix;
use WildPHP\Messages\Interfaces\IncomingMessageInterface;
use WildPHP\Messages\Traits\ChannelTrait;
use WildPHP\Messages\Traits\PrefixTrait;

/**
 * Class WHO
 * @package WildPHP\Messages
 *
 * Syntax: prefix WHO nickname/channel options
 */
class Who extends BaseIRCMessage implements IncomingMessageInterface, OutgoingMessageInterface
{
    protected static $verb = 'WHO';

    use PrefixTrait;
    use ChannelTrait;

    /**
     * @var string
     */
    protected $options = '';

    /**
     * WHO constructor.
     *
     * @param string $channel
     * @param string $options
     */
    public function __construct(string $channel, string $options = '')
    {
        $this->setChannel($channel);
        $this->setOptions($options);
    }

    /**
     * @param IncomingMessage $incomingMessage
     *
     * @return self
     * @throws \InvalidArgumentException
     */
    public static function fromIncomingMessage(IncomingMessage $incomingMessage): self
    {
        if ($incomingMessage->getVerb() != self::getVerb()) {
            throw new \InvalidArgumentException('Expected incoming ' . self::getVerb() . '; got ' . $incomingMessage->getVerb());
        }

        $prefix = Prefix::fromIncomingMessage($incomingMessage);
        $args = $incomingMessage->getArgs();
        $channel = array_shift($args);
        $options = array_shift($args);

        $object = new self($channel, $options);
        $object->setPrefix($prefix);

        return $object;
    }

    /**
     * @return string
     */
    public function getOptions(): string
    {
        return $this->options;
    }

    /**
     * @param string $options
     */
    public function setOptions(string $options)
    {
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $options = $this->getOptions();

        return 'WHO ' . $this->getChannel() . (!empty($options) ? ' ' . $options : '') . "\r\n";
    }
}