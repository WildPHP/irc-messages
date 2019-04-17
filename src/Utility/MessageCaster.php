<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Messages\Utility;


use WildPHP\Messages\Exceptions\CastException;
use WildPHP\Messages\Interfaces\IncomingMessageInterface;
use WildPHP\Messages\Interfaces\IrcMessageInterface;
use WildPHP\Messages\Interfaces\OutgoingMessageInterface;

class MessageCaster
{
    /**
     * @param IrcMessageInterface $incomingIrcMessage
     *
     * @return OutgoingMessageInterface|IncomingMessageInterface
     * @throws \ReflectionException
     * @throws CastException
     */
    public static function castMessage(IrcMessageInterface $incomingIrcMessage)
    {
        $verb = $incomingIrcMessage->getVerb();

        if (is_numeric($verb)) {
            $expectedClass = RplTranslateEnum::translateNumeric($verb);
        } else {
            $verb = ucfirst($verb);
            $expectedClass = '\\WildPHP\\Messages\\' . $verb;
        }

        if (!class_exists($expectedClass)) {
            throw new CastException();
        }

        $reflection = new \ReflectionClass($expectedClass);

        if (!$reflection->implementsInterface(IncomingMessageInterface::class) && !$reflection->implementsInterface(OutgoingMessageInterface::class)) {
            throw new CastException();
        }

        /** @noinspection PhpUndefinedMethodInspection */
        return $expectedClass::fromIncomingMessage($incomingIrcMessage);
    }
}