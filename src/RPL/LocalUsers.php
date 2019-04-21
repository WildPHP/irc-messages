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

class LocalUsers extends BaseIRCMessageImplementation implements IncomingMessageInterface
{
    use NicknameTrait;
    use ServerTrait;
    use MessageTrait;

    protected static $verb = '265';

    /**
     * @var int
     */
    protected $currentUsers = -1;

    /**
     * @var int
     */
    protected $maximumUsers = -1;

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

        if (count($incomingMessage->getArgs() === 4)) {
            [$nickname, $current, $maximum, $message] = $incomingMessage->getArgs();
        } else {
            [$nickname, $message] = $incomingMessage->getArgs();
        }
        $server = $incomingMessage->getPrefix();


        $object = new self();
        $object->setNickname($nickname);
        $object->setServer($server);
        $object->setMessage($message);
        $object->setCurrentUsers($current ?? -1);
        $object->setMaximumUsers($maximum ?? -1);
        $object->setTags($incomingMessage->getTags());

        return $object;
    }

    /**
     * @return int
     */
    public function getCurrentUsers(): int
    {
        return $this->currentUsers;
    }

    /**
     * @param int $currentUsers
     */
    public function setCurrentUsers(int $currentUsers): void
    {
        $this->currentUsers = $currentUsers;
    }

    /**
     * @return int
     */
    public function getMaximumUsers(): int
    {
        return $this->maximumUsers;
    }

    /**
     * @param int $maximumUsers
     */
    public function setMaximumUsers(int $maximumUsers): void
    {
        $this->maximumUsers = $maximumUsers;
    }
}
