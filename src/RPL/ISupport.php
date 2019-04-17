<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Messages\RPL;

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
 * Syntax: :server 005 nickname VARIABLE[=key] VARIABLE[=key] ... :greeting
 */
class ISupport extends BaseIRCMessageImplementation implements IncomingMessageInterface
{
    use NicknameTrait;
    use ServerTrait;
    use MessageTrait;

    protected static $verb = '005';

    protected $variables = [];

    /**
     * @param IrcMessageInterface $incomingMessage
     *
     * @return self
     */
    public static function fromIncomingMessage(IrcMessageInterface $incomingMessage): self
    {
        if ($incomingMessage->getVerb() != self::getVerb()) {
            throw new \InvalidArgumentException('Expected incoming ' . self::getVerb() . '; got ' . $incomingMessage->getVerb());
        }

        $args = $incomingMessage->getArgs();
        $nickname = array_shift($args);
        $server = $incomingMessage->getPrefix();
        $message = array_pop($args);

        $variables = [];
        foreach ($args as $arrayKey => $value) {
            $parts = explode('=', $value);
            $key = strtolower($parts[0]);
            $value = !empty($parts[1]) ? $parts[1] : true;
            $variables[$key] = $value;
        }

        $object = new self();
        $object->setNickname($nickname);
        $object->setServer($server);
        $object->setVariables($variables);
        $object->setMessage($message);
        $object->setTags($incomingMessage->getTags());

        return $object;
    }

    /**
     * @return array
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    /**
     * @param array $variables
     */
    public function setVariables(array $variables)
    {
        $this->variables = $variables;
    }
}