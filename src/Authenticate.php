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
use WildPHP\Messages\Interfaces\IncomingMessageInterface;
use WildPHP\Messages\Interfaces\IrcMessageInterface;
use WildPHP\Messages\Interfaces\OutgoingMessageInterface;

/**
 * Class Authenticate
 * @package WildPHP\Messages
 *
 * Syntax: AUTHENTICATE response
 * @TODO look into the documentation
 */
class Authenticate extends BaseIRCMessageImplementation implements IncomingMessageInterface, OutgoingMessageInterface
{
    protected static $verb = 'AUTHENTICATE';

    /**
     * @var string
     */
    protected $response = '';

    /**
     * Authenticate constructor.
     *
     * @param string $response
     */
    public function __construct(string $response)
    {
        $this->setResponse($response);
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

        [$response] = $incomingMessage->getArgs();

        $object = new self($response);
        $object->setTags($incomingMessage->getTags());

        return $object;
    }

    /**
     * @return string
     */
    public function getResponse(): string
    {
        return $this->response;
    }

    /**
     * @param string $response
     */
    public function setResponse(string $response)
    {
        $this->response = $response;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'AUTHENTICATE ' . $this->getResponse() . "\r\n";
    }
}
