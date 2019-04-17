<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

declare(strict_types=1);

namespace WildPHP\Messages;

use WildPHP\Messages\Generics\BaseIRCMessageImplementation;
use WildPHP\Messages\Interfaces\OutgoingMessageInterface;
use WildPHP\Messages\Traits\ServerTrait;

/**
 * Class NAMES
 * @package WildPHP\Messages
 *
 * Syntax: NAMES [channel](,[channel],...) ([server])
 */
class Names extends BaseIRCMessageImplementation implements OutgoingMessageInterface
{
    protected static $verb = 'NAMES';

    use Traits\ChannelsTrait;
    use ServerTrait;

    /**
     * NAMES constructor.
     *
     * @param string[]|string $channels
     * @param string $server
     */
    public function __construct($channels, string $server = '')
    {
        if (is_string($channels)) {
            $channels = [$channels];
        }

        $this->setChannels($channels);
        $this->setServer($server);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $server = !empty($this->getServer()) ? ' ' . $this->getServer() : '';
        return 'NAMES ' . implode(',', $this->getChannels()) . $server;
    }
}
