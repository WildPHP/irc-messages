<?php
/**
 * Copyright 2019 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

declare(strict_types=1);

namespace WildPHP\Messages\RPL;

use InvalidArgumentException;
use WildPHP\Messages\Generics\BaseIRCMessageImplementation;
use WildPHP\Messages\Interfaces\IncomingMessageInterface;
use WildPHP\Messages\Interfaces\IrcMessageInterface;
use WildPHP\Messages\Traits\NicknameTrait;
use WildPHP\Messages\Traits\ServerTrait;

class MyInfo extends BaseIRCMessageImplementation implements IncomingMessageInterface
{
    use NicknameTrait;
    use ServerTrait;

    protected static $verb = '004';

    /**
     * @var string
     */
    protected $version = '';

    /**
     * @var array
     */
    protected $userModes = [];

    /**
     * @var array
     */
    protected $channelModes = [];

    /**
     * @var array
     */
    protected $channelModesParam = [];
    /**
     * @param IrcMessageInterface $incomingMessage
     *
     * @return mixed
     */
    public static function fromIncomingMessage(IrcMessageInterface $incomingMessage)
    {
        if ($incomingMessage->getVerb() !== self::getVerb()) {
            throw new InvalidArgumentException(sprintf(
                'Expected incoming %s; got %s',
                self::getVerb(),
                $incomingMessage->getVerb()
            ));
        }

        [
          $nickname,
          $server,
          $version,
          $userModes,
          $channelModes,
          $channelModesParam,
        ] = $incomingMessage->getArgs() + [5 => ''];

        $object = new self();
        $object->setNickname($nickname);
        $object->setServer($server);
        $object->setVersion($version);
        $object->setUserModes(array_filter(str_split($userModes)));
        $object->setChannelModes(array_filter(str_split($channelModes)));
        $object->setChannelModesWithParameter(array_filter(str_split($channelModesParam)));
        $object->setTags($incomingMessage->getTags());

        return $object;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    /**
     * @return array
     */
    public function getUserModes(): array
    {
        return $this->userModes;
    }

    /**
     * @param array $userModes
     */
    public function setUserModes(array $userModes): void
    {
        $this->userModes = $userModes;
    }

    /**
     * @return array
     */
    public function getChannelModes(): array
    {
        return $this->channelModes;
    }

    /**
     * @param array $channelModes
     */
    public function setChannelModes(array $channelModes): void
    {
        $this->channelModes = $channelModes;
    }

    /**
     * @return array
     */
    public function getChannelModesWithParameter(): array
    {
        return $this->channelModesParam;
    }

    /**
     * @param array $channelModesParam
     */
    public function setChannelModesWithParameter(array $channelModesParam): void
    {
        $this->channelModesParam = $channelModesParam;
    }
}
