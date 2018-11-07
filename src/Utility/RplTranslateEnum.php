<?php
/**
 * Created by PhpStorm.
 * User: rkerkhof
 * Date: 07/11/2018
 * Time: 15:37
 */

namespace WildPHP\Messages\Utility;


use WildPHP\Messages\RPL\EndOfNames;
use WildPHP\Messages\RPL\ISupport;
use WildPHP\Messages\RPL\NamReply;
use WildPHP\Messages\RPL\Topic;
use WildPHP\Messages\RPL\Welcome;
use WildPHP\Messages\RPL\WhosPcRpl;

class RplTranslateEnum
{
    // This is necessary because PHP doesn't allow classes with numeric names.
    protected static $numericMessageList = [
        '001' => Welcome::class,
        '005' => ISupport::class,
        '332' => Topic::class,
        '353' => NamReply::class,
        '354' => WhosPcRpl::class,
        '366' => EndOfNames::class
    ];

    /**
     * @param string $numericVerb
     * @return bool|mixed
     */
    public static function translateNumeric(string $numericVerb)
    {
        if (!array_key_exists($numericVerb, self::$numericMessageList)) {
            return false;
        }

        return self::$numericMessageList[$numericVerb];
    }
}