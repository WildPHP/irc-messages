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
use WildPHP\Messages\Interfaces\IncomingMessageInterface;
use WildPHP\Messages\Interfaces\IrcMessageInterface;
use WildPHP\Messages\Traits\MessageTrait;
use WildPHP\Messages\Traits\NicknameTrait;
use WildPHP\Messages\Traits\ServerTrait;

/**
 * Class RPL_WELCOME
 * @package WildPHP\Messages
 *
 * Syntax: :server 001 nickname :greeting
 */
class Welcome extends BaseIRCMessageImplementation implements IncomingMessageInterface
{
    use NicknameTrait;
    use ServerTrait;
    use MessageTrait;

    protected static $verb = '001';

    /**
     * @param IrcMessageInterface $incomingMessage
     *
     * @return self
     */
    public static function fromIncomingMessage(IrcMessageInterface $incomingMessage): self
    {
        if ($incomingMessage->getVerb() !== static::getVerb()) {
            throw new InvalidArgumentException(sprintf(
                'Expected incoming %s; got %s',
                static::getVerb(),
                $incomingMessage->getVerb()
            ));
        }

        [$nickname, $message] = $incomingMessage->getArgs();
        $server = $incomingMessage->getPrefix();

        $object = new static();
        $object->setNickname($nickname);
        $object->setServer($server);
        $object->setMessage($message);
        $object->setTags($incomingMessage->getTags());

        return $object;
    }
}
