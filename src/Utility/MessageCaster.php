<?php
/**
 * Created by PhpStorm.
 * User: rkerkhof
 * Date: 05/11/2018
 * Time: 15:12
 */

namespace WildPHP\Messages\Utility;


use WildPHP\Messages\Exceptions\CastException;
use WildPHP\Messages\Generics\IncomingMessage;
use WildPHP\Messages\Interfaces\IncomingMessageInterface;
use WildPHP\Messages\Interfaces\OutgoingMessageInterface;

class MessageCaster
{
    /**
     * @param IncomingMessage $incomingIrcMessage
     *
     * @return OutgoingMessageInterface|IncomingMessageInterface
     * @throws \ReflectionException
     * @throws CastException
     */
    public static function castMessage(IncomingMessage $incomingIrcMessage)
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