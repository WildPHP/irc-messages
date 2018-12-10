<?php
/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Messages\Traits;


use WildPHP\Messages\Interfaces\PrefixInterface;

trait PrefixTrait
{
    /**
     * @var PrefixInterface
     */
    protected $prefix = null;

    /**
     * @return PrefixInterface
     */
    public function getPrefix(): PrefixInterface
    {
        return $this->prefix;
    }

    /**
     * @param PrefixInterface $prefix
     */
    public function setPrefix(PrefixInterface $prefix)
    {
        $this->prefix = $prefix;
    }
}