<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

declare(strict_types=1);

namespace WildPHP\Messages\RPL;

use InvalidArgumentException;
use WildPHP\Messages\Generics\BaseIRCMessageImplementation;
use WildPHP\Messages\Generics\Prefix;
use WildPHP\Messages\Interfaces\IncomingMessageInterface;
use WildPHP\Messages\Interfaces\IrcMessageInterface;
use WildPHP\Messages\Traits\MessageTrait;
use WildPHP\Messages\Traits\NicknameTrait;
use WildPHP\Messages\Traits\ServerTrait;

/**
 * Class MotdStart
 * @package WildPHP\Messages\RPL
 *
 * Syntax: server 375 nickname :- server Message Of The Day -
 */
class MotdStart extends BaseIRCMessageImplementation implements IncomingMessageInterface
{
    use ServerTrait;
    use NicknameTrait;
    use MessageTrait;

    protected static $verb = '375';

    /**
     * @param IrcMessageInterface $incomingMessage
     *
     * @return self
     * @throws InvalidArgumentException
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
        [$nickname, $message] = $incomingMessage->getArgs();

        $object = new self();
        $object->setNickname($nickname);

        // cut off the leading '- ' and the ' -' prefix
        $object->setMessage(substr($message, 2, -2));
        $object->setServer($prefix->getHostname());
        $object->setTags($incomingMessage->getTags());

        return $object;
    }
}
