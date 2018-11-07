<?php
/**
 * Created by PhpStorm.
 * User: rkerkhof
 * Date: 05/11/2018
 * Time: 15:12
 */

namespace WildPHP\Messages;


use WildPHP\Messages\Exceptions\UncastableMessageException;

class MessageCaster
{
    // This is necessary because PHP doesn't allow classes with numeric names.
    protected static $numericMessageList = [
        '001' => 'RPL_WELCOME',
        '005' => 'RPL_ISUPPORT',
        '332' => 'RPL_TOPIC',
        '353' => 'RPL_NAMREPLY',
        '354' => 'RPL_WHOSPCRPL',
        '366' => 'RPL_ENDOFNAMES',
    ];

    /**
     * @param IncomingIrcMessage $incomingIrcMessage
     *
     * @return SendableMessage|ReceivableMessage
     * @throws \ReflectionException
     * @throws UncastableMessageException
     */
    public static function specializeIrcMessage(IncomingIrcMessage $incomingIrcMessage)
    {
        $verb = $incomingIrcMessage->getVerb();

        if (is_numeric($verb)) {
            $verb = array_key_exists($verb, self::$numericMessageList) ? self::$numericMessageList[$verb] : $verb;
        }

        $expectedClass = '\WildPHP\Core\Connection\IRCMessages\\' . $verb;

        if (!class_exists($expectedClass)) {
            throw new UncastableMessageException();
        }

        $reflection = new \ReflectionClass($expectedClass);

        if (!$reflection->implementsInterface(ReceivableMessage::class) && !$reflection->implementsInterface(SendableMessage::class)) {
            throw new UncastableMessageException();
        }

        /** @var ReceivableMessage|SendableMessage $expectedClass */
        return $expectedClass::fromIncomingIrcMessage($incomingIrcMessage);
    }
}