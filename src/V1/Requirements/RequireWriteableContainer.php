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
 * @package   DIContainers/V1/Requirements
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2016-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://ganbarodigital.github.io/php-mv-di-containers
 */

namespace GanbaroDigital\DIContainers\V1\Requirements;

use GanbaroDigital\DIContainers\V1\Exceptions\ContainerIsReadOnly;
use GanbaroDigital\DIContainers\V1\Exceptions\DIContainersExceptions;
use GanbaroDigital\DIContainers\V1\Interfaces\FactoryList;

/**
 * make sure that we are working with a writeable container
 */
class RequireWriteableContainer
{
    /**
     * a list of exception functions
     * @var FactoryList
     */
    private $exceptions;

    /**
     * a call stack filter to apply
     * @var array
     */
    private $defaultCallStackFilter = [];

    /**
     * create a requirement that is ready to execute
     *
     * @param  FactoryList|null $exceptions
     *         the functions to call when we want to throw an exception
     * @param  array $callStackFilter
     *         a list of classes to filter from the call stack analysis
     * @return RequireWriteableContainer
     */
    static public function apply(FactoryList $exceptions = null, array $callStackFilter = [])
    {
        return new static($exceptions, $callStackFilter);
    }

    /**
     * our constructor
     *
     * @param  FactoryList|null $exceptions
     *         the functions to call when we want to throw an exception
     * @param  array $callStackFilter
     *         a list of classes to filter from the call stack analysis
     */
    protected function __construct(FactoryList $exceptions = null, array $callStackFilter = [])
    {
        if ($exceptions === null) {
            $exceptions = new DIContainersExceptions;
        }
        $this->exceptions = $exceptions;
        $this->callStackFilter = $callStackFilter;
    }

    /**
     * throws exception if our requirement is not met
     *
     * @param  FactoryList $container
     *         the container to examine
     * @param  string $fieldOrVarName
     *         what is the name of $container in the calling code?
     * @return void
     */
    public function to(FactoryList $container, $fieldOrVarName = '$container')
    {
        if ($container->isReadWrite()) {
            return true;
        }

        throw $this->exceptions["ContainerIsReadOnly::newFromInputParameter"]($container, $fieldOrVarName, [], null, $this->callStackFilter);
    }
}
