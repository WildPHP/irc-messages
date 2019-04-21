<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

declare(strict_types=1);

namespace WildPHP\Messages\Utility;

use WildPHP\Messages\RPL\Created;
use WildPHP\Messages\RPL\EndOfMotd;
use WildPHP\Messages\RPL\EndOfNames;
use WildPHP\Messages\RPL\EndOfWho;
use WildPHP\Messages\RPL\GlobalUsers;
use WildPHP\Messages\RPL\ISupport;
use WildPHP\Messages\RPL\LocalUsers;
use WildPHP\Messages\RPL\LoggedIn;
use WildPHP\Messages\RPL\LUserChannels;
use WildPHP\Messages\RPL\LUserClient;
use WildPHP\Messages\RPL\LUserMe;
use WildPHP\Messages\RPL\LUserOp;
use WildPHP\Messages\RPL\LUserUnknown;
use WildPHP\Messages\RPL\Motd;
use WildPHP\Messages\RPL\MotdStart;
use WildPHP\Messages\RPL\MyInfo;
use WildPHP\Messages\RPL\NamReply;
use WildPHP\Messages\RPL\SaslSuccess;
use WildPHP\Messages\RPL\StatsConn;
use WildPHP\Messages\RPL\Topic;
use WildPHP\Messages\RPL\TopicWhoTime;
use WildPHP\Messages\RPL\Welcome;
use WildPHP\Messages\RPL\WhosPcRpl;
use WildPHP\Messages\RPL\YourHost;

class RplTranslateEnum
{
    // This is necessary because PHP doesn't allow classes with numeric names.
    protected static $numericMessageList = [
        '001' => Welcome::class,
        '002' => YourHost::class,
        '003' => Created::class,
        '004' => MyInfo::class,
        '005' => ISupport::class,
        '250' => StatsConn::class,
        '251' => LUserClient::class,
        '252' => LUserOp::class,
        '253' => LUserUnknown::class,
        '254' => LUserChannels::class,
        '255' => LUserMe::class,
        '265' => LocalUsers::class,
        '266' => GlobalUsers::class,
        '315' => EndOfWho::class,
        '332' => Topic::class,
        '333' => TopicWhoTime::class,
        '353' => NamReply::class,
        '354' => WhosPcRpl::class,
        '366' => EndOfNames::class,
        '372' => Motd::class,
        '375' => MotdStart::class,
        '376' => EndOfMotd::class,
        '900' => LoggedIn::class,
        '903' => SaslSuccess::class
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
