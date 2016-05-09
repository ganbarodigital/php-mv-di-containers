<?php

/**
 * Copyright (c) 2016-present Ganbaro Digital Ltd
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  Libraries
 * @package   DIContainers/V1/FactoryList
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2016-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://ganbarodigital.github.io/php-mv-di-containers
 */

namespace GanbaroDigital\DIContainers\V1\FactoryList\Containers;

use GanbaroDigital\DIContainers\V1\Exceptions\DIContainersExceptions;
use GanbaroDigital\DIContainers\V1\Exceptions\NoSuchFactory;
use GanbaroDigital\DIContainers\V1\Exceptions\NotAFactory;
use GanbaroDigital\DIContainers\V1\Exceptions\NotAListOfFactories;
use GanbaroDigital\DIContainers\V1\Exceptions\NotAFactoryList;
use GanbaroDigital\DIContainers\V1\Interfaces\EntityContainer;
use GanbaroDigital\DIContainers\V1\Interfaces\FactoryList;
use GanbaroDigital\MissingBits\Entities\WriteProtectedEntity;
use GanbaroDigital\MissingBits\Entities\WriteProtectTab;

/**
 * An entity which holds a list of registered factories. Can be
 * accessed as an array.
 */
class FactoryListContainer
  implements FactoryList, WriteProtectedEntity
{
    // satisfies WriteProtectedEntity interface
    use WriteProtectTab;

    /**
     * the list of factories
     *
     * the 'key' is the alias for the instance. We recommend using either the
     * fully-qualified class name, or the class name with no namespace
     *
     * the 'value' is a callable that returns something. We recommend that this
     * always returns an object of some kind, but we deliberately do not
     * enforce that, in case you find new and novel ways to use this provider
     *
     * @var array
     */
    private $factories = [];

    /**
     * the list of exceptions to throw
     *
     * @var FactoryList
     */
    private $exceptions;

    /**
     * create a managed list of factories
     *
     * the array has the following format:
     * - 'key' is the name or alias for your factor
     * - 'value' is a callable that will return something
     *
     * examples of keys include:
     *
     * - 'NotAFactoryList::newFromVar'
     * - 'NotAFactory::newFromNonCallable'
     *
     * the only requirements on 'value' are that
     * - it is a callable
     *
     * @param array $factories
     *        the list of factories to define
     * @param FactoryList $exceptions
     *        the list of exceptions to throw
     */
    public function __construct($factories = [], FactoryList $exceptions = null)
    {
        // make sure we have some exceptions
        if ($exceptions === null) {
            $exceptions = new DIContainersExceptions;
        }

        // normally, we would not cache the exceptions in an object
        // but in this case, some of our public methods need to throw exceptions,
        // and cannot accept an exceptions list as a parameter
        $this->exceptions = $exceptions;

        // robustness!
        if (!is_array($factories)) {
            throw $exceptions['NotAListOfFactories::newFromVar']($factories, '\$factories');
        }
        foreach ($factories as $name => $factory) {
            if (!is_callable($factory)) {
                throw $exceptions['NotAFactory::newFromNonCallable']($name, $factory);
            }
        }

        // remember the factories we've been given
        $this->factories = $factories;

        // put ourselves into read-only mode
        $this->setReadOnly();
    }

    /**
     * get a factory for you to call
     *
     * @param  string $factoryName
     *         a valid key into the list of factories stored in this entity
     * @return callable
     *
     * @throws NoSuchFactory
     *         if we can't find a factory for $factoryName
     */
    public function offsetGet($factoryName)
    {
        // do we have a factory for this?
        if (isset($this->factories[$factoryName])) {
            return $this->factories[$factoryName];
        }

        // if we get here, then we do not have a factory by that name
        throw $this->exceptions['NoSuchFactory::newFromFactoryName']($factoryName);
    }

    /**
     * do we have a factory for a given instance alias?
     *
     * @param string $factoryName
     *        the factory to look for
     */
    public function offsetExists($factoryName)
    {
        if (isset($this->factories[$factoryName])) {
            return true;
        }

        return false;
    }

    /**
     * set a factory for a given instance alias
     *
     * @param string $factoryName
     *        the name or alias to give to $factory
     * @param callable $factory
     *        the factory to add to our container
     *
     * @throws NotAFactory
     *         if $factory isn't callable
     */
    public function offsetSet($factoryName, $factory)
    {
        // are we allowed to edit this container?
        $this->requireReadWrite();

        // do we have an acceptable factory?
        if (!is_callable($factory)) {
            throw $this->exceptions['NotAFactory::newFromNonCallable']($factoryName, $factory);
        }

        // if we get here, all is good
        $this->factories[$factoryName] = $factory;
    }

    /**
     * forget a factory
     *
     * @param string $factoryName
     *        the factory that we want to forget
     */
    public function offsetUnset($factoryName)
    {
        // are we allowed to edit this container?
        $this->requireReadWrite();

        // it's PHP convention that attempting to unset() something that is
        // already unset() is not considered an error
        if (!isset($this->factories[$factoryName])) {
            return;
        }

        // forget the factory
        unset($this->factories[$factoryName]);
    }

    /**
     * return the full list of factories as a real PHP array
     *
     * @return array
     */
    public function getList()
    {
        return $this->factories;
    }

    /**
     * are we allowed to edit this container?
     *
     * @return void
     * @throws ContainerIsReadOnly
     */
    protected function requireReadWrite()
    {
        // are we allowed to edit this container?
        if ($this->isReadOnly()) {
            throw $this->exceptions['ContainerIsReadOnly::newFromContainer']($this);
        }
    }
}
