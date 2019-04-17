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
 * Class WHOWAS
 * @package WildPHP\Messages
 *
 * Syntax: WHOIS nickname(,nickname,...) (count] (server))
 */
class WhoWas extends BaseIRCMessageImplementation implements OutgoingMessageInterface
{
    protected static $verb = 'WHOIS';

    use ServerTrait;

    /**
     * @var int
     */
    protected $count = 0;

    /**
     * @var string[]
     */
    protected $nicknames = '';

    /**
     * WHOWAS constructor.
     *
     * @param string[]|string $nicknames
     * @param int $count
     * @param string $server
     */
    public function __construct($nicknames, int $count = 0, string $server = '')
    {
        if (is_string($nicknames)) {
            $nicknames = [$nicknames];
        }

        $this->setNicknames($nicknames);
        $this->setCount($count);
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
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount(int $count)
    {
        $this->count = $count;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $count = !empty($this->getCount()) ? ' ' . trim($this->getCount() . ' ' . $this->getServer()) : '';
        return 'WHOWAS ' . implode(',', $this->getNicknames()) . $count;
    }
}
