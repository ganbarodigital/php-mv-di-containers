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
 * @package   DIContainers/V1/Exceptions
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2016-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://ganbarodigital.github.io/php-mv-di-containers
 */

namespace GanbaroDigital\DIContainers\V1\Exceptions;
use GanbaroDigital\DIContainers\V1\FactoryList\Containers\FactoryListContainer;

/**
 * a list of the exceptions thrown by this library, or by any libraries that
 * we rely on
 */
class DIContainersExceptions extends FactoryListContainer
{
    public function __construct()
    {
        // all of the exceptions that our library can create, and the means
        // to create them
        $ourExceptions = [
            'ContainerIsReadOnly::newFromInputParameter' => [ ContainerIsReadOnly::class, 'newFromInputParameter' ],
            'ContainerIsReadOnly::newFromVar' => [ ContainerIsReadOnly::class, 'newFromVar' ],
            'NoSuchFactory::newFromInputParameter' => [ NoSuchFactory::class, 'newFromInputParameter' ],
            'NoSuchFactory::newFromVar' => [ NoSuchFactory::class, 'newFromVar' ],
            'NotAFactory::newFromInputParameter' => [ NotAFactory::class, 'newFromInputParameter' ],
            'NotAFactory::newFromVar' => [ NotAFactory::class, 'newFromVar' ],
            'NotAListOfFactories::newFromInputParameter' => [ NotAListOfFactories:: class, 'newFromInputParameter' ],
            'NotAListOfFactories::newFromVar' => [ NotAListOfFactories:: class, 'newFromVar' ],
        ];

        // special case - we have to pass $this into our parent constructor
        // to prevent it attempting to create a new instance of this class :)
        parent::__construct($ourExceptions, $this);
    }
}
