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

namespace GanbaroDigitalTest\DIContainers\V1\Exceptions;

use RuntimeException;
use PHPUnit_Framework_TestCase;
use GanbaroDigital\DIContainers\V1\Exceptions\ContainerIsReadOnly;
use GanbaroDigital\DIContainers\V1\Exceptions\DIContainersExceptions;
use GanbaroDigital\DIContainers\V1\Exceptions\NoSuchFactory;
use GanbaroDigital\DIContainers\V1\Exceptions\NotAFactory;
use GanbaroDigital\DIContainers\V1\Exceptions\NotAListOfFactories;
use GanbaroDigital\DIContainers\V1\Interfaces\FactoryList;

/**
 * @coversDefaultClass GanbaroDigital\DIContainers\V1\Exceptions\DIContainersExceptions
 */
class DIContainersExceptionsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     */
    public function testCanInstantiate()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $unit = new DIContainersExceptions();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(DIContainersExceptions::class, $unit);
    }

    /**
     * @covers ::offsetGet
     */
    public function test_has_factory_for_ContainerIsReadOnly_newFromInputParameter()
    {
        // ----------------------------------------------------------------
        // setup your test

        $unit = new DIContainersExceptions();

        // ----------------------------------------------------------------
        // perform the change

        $factory = $unit['ContainerIsReadOnly::newFromInputParameter'];
        $exception = $factory('trout', 'fish');

        // ----------------------------------------------------------------
        // test the results
        //
        // we prove that we got the correct builder by checking the type
        // of the exception that it built

        $this->assertInstanceOf(ContainerIsReadOnly::class, $exception);
    }

    /**
     * @covers ::offsetGet
     */
    public function test_has_factory_for_ContainerIsReadOnly_newFromVar()
    {
        // ----------------------------------------------------------------
        // setup your test

        $unit = new DIContainersExceptions();

        // ----------------------------------------------------------------
        // perform the change

        $factory = $unit['ContainerIsReadOnly::newFromVar'];
        $exception = $factory('trout', 'fish');

        // ----------------------------------------------------------------
        // test the results
        //
        // we prove that we got the correct builder by checking the type
        // of the exception that it built

        $this->assertInstanceOf(ContainerIsReadOnly::class, $exception);
    }

    /**
     * @covers ::offsetGet
     */
    public function test_has_factory_for_NoSuchFactory_newFromInputParameter()
    {
        // ----------------------------------------------------------------
        // setup your test

        $unit = new DIContainersExceptions();

        // ----------------------------------------------------------------
        // perform the change

        $factory = $unit['NoSuchFactory::newFromInputParameter'];
        $exception = $factory('trout', 'fish');

        // ----------------------------------------------------------------
        // test the results
        //
        // we prove that we got the correct builder by checking the type
        // of the exception that it built

        $this->assertInstanceOf(NoSuchFactory::class, $exception);
    }

    /**
     * @covers ::offsetGet
     */
    public function test_has_factory_for_NoSuchFactory_newFromVar()
    {
        // ----------------------------------------------------------------
        // setup your test

        $unit = new DIContainersExceptions();

        // ----------------------------------------------------------------
        // perform the change

        $factory = $unit['NoSuchFactory::newFromVar'];
        $exception = $factory('trout', 'fish');

        // ----------------------------------------------------------------
        // test the results
        //
        // we prove that we got the correct builder by checking the type
        // of the exception that it built

        $this->assertInstanceOf(NoSuchFactory::class, $exception);
    }

    /**
     * @covers ::offsetGet
     */
    public function test_has_factory_for_NotAFactory_newFromInputParameter()
    {
        // ----------------------------------------------------------------
        // setup your test

        $unit = new DIContainersExceptions();

        // ----------------------------------------------------------------
        // perform the change

        $factory = $unit['NotAFactory::newFromInputParameter'];
        $exception = $factory('trout', 'fish');

        // ----------------------------------------------------------------
        // test the results
        //
        // we prove that we got the correct builder by checking the type
        // of the exception that it built

        $this->assertInstanceOf(NotAFactory::class, $exception);
    }

    /**
     * @covers ::offsetGet
     */
    public function test_has_factory_for_NotAFactory_newFromVar()
    {
        // ----------------------------------------------------------------
        // setup your test

        $unit = new DIContainersExceptions();

        // ----------------------------------------------------------------
        // perform the change

        $factory = $unit['NotAFactory::newFromVar'];
        $exception = $factory('trout', 'fish');

        // ----------------------------------------------------------------
        // test the results
        //
        // we prove that we got the correct builder by checking the type
        // of the exception that it built

        $this->assertInstanceOf(NotAFactory::class, $exception);
    }

    /**
     * @covers ::offsetGet
     */
    public function test_has_factory_for_NotAListOfFactories_newFromInputParameter()
    {
        // ----------------------------------------------------------------
        // setup your test

        $unit = new DIContainersExceptions();

        // ----------------------------------------------------------------
        // perform the change

        $factory = $unit['NotAListOfFactories::newFromInputParameter'];
        $exception = $factory('trout', 'fish');

        // ----------------------------------------------------------------
        // test the results
        //
        // we prove that we got the correct builder by checking the type
        // of the exception that it built

        $this->assertInstanceOf(NotAListOfFactories::class, $exception);
    }

    /**
     * @covers ::offsetGet
     */
    public function test_has_factory_for_NotAListOfFactories_newFromVar()
    {
        // ----------------------------------------------------------------
        // setup your test

        $unit = new DIContainersExceptions();

        // ----------------------------------------------------------------
        // perform the change

        $factory = $unit['NotAListOfFactories::newFromVar'];
        $exception = $factory('trout', 'fish');

        // ----------------------------------------------------------------
        // test the results
        //
        // we prove that we got the correct builder by checking the type
        // of the exception that it built

        $this->assertInstanceOf(NotAListOfFactories::class, $exception);
    }
}
