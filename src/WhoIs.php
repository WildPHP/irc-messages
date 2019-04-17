<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Messages;

use WildPHP\Messages\Generics\BaseIRCMessageImplementation;
use WildPHP\Messages\Interfaces\OutgoingMessageInterface;
use WildPHP\Messages\Traits\ServerTrait;

/**
 * Class WHOIS
 * @package WildPHP\Messages
 *
 * Syntax: WHOIS (server) nickname(,nickname,...)
 */
class WhoIs extends BaseIRCMessageImplementation implements OutgoingMessageInterface
{
    protected static $verb = 'WHOIS';

    use ServerTrait;

    /**
     * @var string[]
     */
    protected $nicknames = '';

    /**
     * WHOIS constructor.
     *
     * @param string[]|string $nicknames
     * @param string $server
     */
    public function __construct($nicknames, string $server = '')
    {
        if (is_string($nicknames)) {
            $nicknames = [$nicknames];
        }

        $this->setNicknames($nicknames);
        $this->setServer($server);
    }

    /**
     * @return string[]
     */
    public function getNicknames(): array
    {
        return $this->nicknames;
    }

    /**
     * @param string[] $nicknames
     */
    public function setNicknames($nicknames)
    {
        $this->nicknames = $nicknames;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $server = !empty($this->getServer()) ? $this->getServer() . ' ' : '';
        return 'WHOIS ' . $server . implode(',', $this->getNicknames());
    }
}
