<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

declare(strict_types=1);

namespace WildPHP\Messages\Utility;

use WildPHP\Messages\RPL\EndOfMotd;
use WildPHP\Messages\RPL\EndOfNames;
use WildPHP\Messages\RPL\ISupport;
use WildPHP\Messages\RPL\Motd;
use WildPHP\Messages\RPL\MotdStart;
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
        '366' => EndOfNames::class,
        '372' => Motd::class,
        '375' => MotdStart::class,
        '376' => EndOfMotd::class
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
