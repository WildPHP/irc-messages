<?php
/**
 * Copyright 2018 The WildPHP Team
 *
 * You should have received a copy of the MIT license with the project.
 * See the LICENSE file for more information.
 */

namespace WildPHP\Messages\Traits;


use WildPHP\Messages\Generics\Prefix;

trait PrefixTrait
{
    /**
     * @var Prefix
     */
    protected $prefix = null;

    /**
     * @return Prefix
     */
    public function getPrefix(): Prefix
    {
        return $this->prefix;
    }

    /**
     * @param Prefix $prefix
     */
    public function setPrefix(Prefix $prefix)
    {
        $this->prefix = $prefix;
    }
}