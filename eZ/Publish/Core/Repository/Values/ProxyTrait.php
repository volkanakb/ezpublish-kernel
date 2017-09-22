<?php

/**
 * This file is part of the eZ Publish Kernel package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace eZ\Publish\Core\Repository\Values;

use Generator;

/**
 * Trait for proxies, covers all relevant magic methods and exposes private method to load object.
 *
 * @internal Meant for internal use by Repository, type hint against API object instead.
 */
trait ProxyTrait
{
    /**
     * @var object
     */
    private $object;

    /**
     * @var Generator|null
     */
    private $generator;

    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }

    public function __call($name, $arguments)
    {
        if (isset($this->generator)) {
            $this->loadObject();
        }

        return $this->object->$name(...$arguments);
    }

    public function __invoke(...$args)
    {
        if (isset($this->generator)) {
            $this->loadObject();
        }

        return ($this->object)(...$args);
    }

    public function __get($name)
    {
        if (isset($this->generator)) {
            $this->loadObject();
        }

        return $this->object->$name;
    }

    public function __isset($name)
    {
        if (isset($this->generator)) {
            $this->loadObject();
        }

        return isset($this->object->$name);
    }

    public function __unset($name)
    {
        if (isset($this->generator)) {
            $this->loadObject();
        }

        unset($this->object->$name);
    }

    public function __set($name, $value)
    {
        if (isset($this->generator)) {
            $this->loadObject();
        }

        $this->object->$name = $value;
    }

    public function __toString()
    {
        if (isset($this->generator)) {
            $this->loadObject();
        }

        return (string)$this->object;
    }

    public function __sleep()
    {
        if (isset($this->generator)) {
            $this->loadObject();
        }

        return array('object');
    }

    public function __debugInfo()
    {
        if (isset($this->generator)) {
            $this->loadObject();
        }

        return [
            'object' => $this->object,
        ];
    }

    /**
     * Loads the generator to object value and unset's generator.
     */
    private function loadObject()
    {
        $this->object = $this->generator->current();
        unset($this->generator);
    }
}
