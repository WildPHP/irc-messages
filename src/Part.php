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
use WildPHP\Messages\Traits\MessageTrait;
use WildPHP\Messages\Traits\NicknameTrait;
use WildPHP\Messages\Traits\PrefixTrait;

/**
 * Class PART
 * @package WildPHP\Messages
 *
 * Syntax: prefix PART #channel [:message]
 * Syntax (sender): PART #channels [:message]
 */
class Part extends BaseIRCMessageImplementation implements IncomingMessageInterface, OutgoingMessageInterface
{
    /**
     * @var string
     */
    protected static $verb = 'PART';

    use Traits\ChannelsTrait;
    use NicknameTrait;
    use PrefixTrait;
    use MessageTrait;

    /**
     * PART constructor.
     *
     * @param $channels
     * @param string $message
     */
    public function __construct($channels, $message = '')
    {
        if (!is_array($channels)) {
            $channels = [$channels];
        }

        $this->setChannels($channels);
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
        $args = $incomingMessage->getArgs();
        $channel = $args[0];
        $message = $args[1] ?? '';

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
        $channels = implode(',', $this->getChannels());
        $message = $this->getMessage();

        return 'PART ' . $channels . (!empty($message) ? ' :' . $message : '') . "\r\n";
    }
}
