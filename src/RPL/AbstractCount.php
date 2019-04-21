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
 * Class AbstractCount
 * @package WildPHP\Messages\RPL
 *
 * Syntax: server [numeric] count :message
 */
abstract class AbstractCount extends BaseIRCMessageImplementation implements IncomingMessageInterface
{
    use ServerTrait;
    use NicknameTrait;
    use MessageTrait;

    protected static $verb = '000';

    /**
     * @var int
     */
    protected $count;

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

        [$nickname, $count, $message] = $incomingMessage->getArgs();
        $server = $incomingMessage->getPrefix();

        $object = new static();
        $object->setNickname($nickname);
        $object->setServer($server);
        $object->setMessage($message);
        $object->setCount((int) $count);
        $object->setTags($incomingMessage->getTags());

        return $object;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount(int $count): void
    {
        $this->count = $count;
    }
}
