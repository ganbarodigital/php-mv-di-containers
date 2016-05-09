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
 * @link      http://ganbarodigital.github.io/php-mv-instance-providers
 */

namespace GanbaroDigitalTest\DIContainers\V1\FactoryList\Containers;

use GanbaroDigital\DIContainers\V1\Exceptions\DIContainersExceptions;
use GanbaroDigital\DIContainers\V1\FactoryList\Containers\FactoryListContainer;
use GanbaroDigital\DIContainers\V1\Interfaces\FactoryList;
use InvalidArgumentException;
use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass GanbaroDigital\DIContainers\V1\FactoryList\Containers\FactoryListContainer
 */
class FactoryListContainerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     */
    public function testCanInstantiate()
    {
        // ----------------------------------------------------------------
        // setup your test

        $factories = [
            'dummy' => function() { return "dummy!"; }
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new FactoryListContainer($factories);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(FactoryListContainer::class, $unit);
    }

    /**
     * @covers ::__construct
     */
    public function testIsFactoryList()
    {
        // ----------------------------------------------------------------
        // setup your test

        $factories = [
            'dummy' => function() { return "dummy!"; }
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new FactoryListContainer($factories);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(FactoryList::class, $unit);
    }

    /**
     * @covers ::__construct
     * @dataProvider provideNonArraysToTest
     * @expectedException GanbaroDigital\DIContainers\V1\Exceptions\NotAListOfFactories
     */
    public function testMustProvideArrayToConstructor($factories)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $unit = new FactoryListContainer($factories);

        // ----------------------------------------------------------------
        // test the results
    }

    /**
     * @covers ::__construct
     * @dataProvider provideNonCallablesToTest
     * @expectedException GanbaroDigital\DIContainers\V1\Exceptions\NotAFactory
     */
    public function testMustProvideListOfCallablesToConstructor($builder)
    {
        // ----------------------------------------------------------------
        // setup your test

        $factories = [
            'dummy' => $builder
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new FactoryListContainer($factories);

        // ----------------------------------------------------------------
        // test the results
    }

    /**
     * @covers ::offsetGet
     */
    public function testCanGetFactory()
    {
        // ----------------------------------------------------------------
        // setup your test

        $expectedBuilder = new FactoryListContainerTest_Builder;
        $factories = [
            'dummy' => $expectedBuilder,
        ];
        $unit = new FactoryListContainer($factories);

        // ----------------------------------------------------------------
        // perform the change

        $actualBuilder = $unit['dummy'];

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedBuilder, $actualBuilder);
    }

    /**
     * @covers ::offsetGet
     * @expectedException GanbaroDigital\DIContainers\V1\Exceptions\NoSuchFactory
     */
    public function testThrowsExceptionIfFactoryNotFound()
    {
        // ----------------------------------------------------------------
        // setup your test

        $factories = [
            'dummy' => new FactoryListContainerTest_Builder
        ];
        $unit = new FactoryListContainer($factories);

        // ----------------------------------------------------------------
        // perform the change

        $actualBuilder = $unit['trout'];

        // ----------------------------------------------------------------
        // test the results
    }

    /**
     * @covers ::offsetGet
     * @expectedException InvalidArgumentException
     */
    public function testCanOverrideExceptionThrownWhenNoFactoryFound()
    {
        // ----------------------------------------------------------------
        // setup your test

        // we have to pass in at least one factory to avoid an exception
        // being thrown
        $factories = [
            'salmon' => function(){ },
        ];

        // FactoryListContainer uses the DIContainersExceptions as its
        // list of exceptions. This is also an FactoryListContainer.
        //
        // we can override this for our test
        $exceptions = new DIContainersExceptions;
        $exceptions->setReadWrite();
        $exceptions['NoSuchFactory::newFromFactoryName'] = new FactoryListContainerTest_Builder;

        $unit = new FactoryListContainer($factories, $exceptions);

        // ----------------------------------------------------------------
        // perform the change

        $actualBuilder = $unit['trout'];

        // ----------------------------------------------------------------
        // test the results
    }

    /**
     * @covers ::offsetExists
     */
    public function testCanCheckIfFactoryRegistered()
    {
        // ----------------------------------------------------------------
        // setup your test

        $factories = [
            'dummy' => new FactoryListContainerTest_Builder
        ];
        $unit = new FactoryListContainer($factories);

        // ----------------------------------------------------------------
        // perform the change

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue(isset($unit['dummy']));
        $this->assertFalse(isset($unit['trout']));
    }

    /**
     * @covers ::offsetSet
     * @covers ::setReadWrite
     * @covers ::requireReadWrite
     */
    public function testCanRegisterAdditionalFactories()
    {
        // ----------------------------------------------------------------
        // setup your test

        $factories = [
            'dummy' => new FactoryListContainerTest_Builder
        ];
        $unit = new FactoryListContainer($factories);
        $this->assertFalse(isset($unit['trout']));

        // ----------------------------------------------------------------
        // perform the change

        $unit->setReadWrite();
        $unit['trout'] = new FactoryListContainerTest_Builder;

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue(isset($unit['dummy']));
        $this->assertTrue(isset($unit['trout']));
    }

    /**
     * @covers ::offsetSet
     * @covers ::setReadWrite
     * @covers ::requireReadWrite
     * @dataProvider provideNonCallablesToTest
     * @expectedException GanbaroDigital\DIContainers\V1\Exceptions\NotAFactory
     */
    public function testMustProvideCallableWhenRegisteringAdditionalFactories($builder)
    {
        // ----------------------------------------------------------------
        // setup your test

        $factories = [
            'dummy' => new FactoryListContainerTest_Builder
        ];
        $unit = new FactoryListContainer($factories);
        $this->assertFalse(isset($unit['trout']));

        // ----------------------------------------------------------------
        // perform the change

        $unit->setReadWrite();
        $unit['trout'] = $builder;

        // ----------------------------------------------------------------
        // test the results
    }

    /**
     * @covers ::offsetUnset
     * @covers ::setReadWrite
     * @covers ::requireReadWrite
     */
    public function testCanForgetAFactory()
    {
        // ----------------------------------------------------------------
        // setup your test

        $factories = [
            'dummy' => new FactoryListContainerTest_Builder
        ];
        $unit = new FactoryListContainer($factories);
        $this->assertTrue(isset($unit['dummy']));

        // ----------------------------------------------------------------
        // perform the change

        $unit->setReadWrite();
        unset($unit['dummy']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse(isset($unit['dummy']));
    }

    /**
     * @covers ::offsetUnset
     * @covers ::setReadWrite
     * @covers ::requireReadWrite
     */
    public function testDoesNotThrowAnExceptionWhenForgettingAFactoryTwice()
    {
        // ----------------------------------------------------------------
        // setup your test

        $factories = [
            'dummy' => new FactoryListContainerTest_Builder
        ];
        $unit = new FactoryListContainer($factories);
        $this->assertTrue(isset($unit['dummy']));

        $unit->setReadWrite();
        unset($unit['dummy']);
        $this->assertFalse(isset($unit['dummy']));

        // ----------------------------------------------------------------
        // perform the change

        unset($unit['dummy']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse(isset($unit['dummy']));
    }

    /**
     * @covers ::getList
     */
    public function testCanGetTheListAsAnArray()
    {
        // ----------------------------------------------------------------
        // setup your test

        $expectedList = [
            'dummy' => new FactoryListContainerTest_Builder
        ];
        $unit = new FactoryListContainer($expectedList);

        // ----------------------------------------------------------------
        // perform the change

        $actualList = $unit->getList();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedList, $actualList);
    }

    /**
     * @covers ::__construct
     * @covers ::setReadOnly
     * @covers ::isReadOnly
     * @covers ::isReadWrite
     */
    public function testIsReadOnlyAfterConstruction()
    {
        // ----------------------------------------------------------------
        // setup your test

        $factories = [
            'dummy' => new FactoryListContainerTest_Builder
        ];
        $unit = new FactoryListContainer($factories);

        // ----------------------------------------------------------------
        // perform the change

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($unit->isReadOnly());
        $this->assertFalse($unit->isReadWrite());
    }

    /**
     * @covers ::offsetSet
     * @covers ::requireReadWrite
     * @expectedException GanbaroDigital\DIContainers\V1\Exceptions\ContainerIsReadOnly
     */
    public function testCannotAddFactoryWhenReadOnly()
    {
        // ----------------------------------------------------------------
        // setup your test

        $factories = [
            'dummy' => new FactoryListContainerTest_Builder
        ];
        $unit = new FactoryListContainer($factories);

        // ----------------------------------------------------------------
        // perform the change

        $unit['trout'] = new FactoryListContainerTest_Builder;

        // ----------------------------------------------------------------
        // test the results
    }

    /**
     * @covers ::offsetUnset
     * @covers ::requireReadWrite
     * @expectedException GanbaroDigital\DIContainers\V1\Exceptions\ContainerIsReadOnly
     */
    public function testCannotForgetFactoryWhenReadOnly()
    {
        // ----------------------------------------------------------------
        // setup your test

        $factories = [
            'dummy' => new FactoryListContainerTest_Builder
        ];
        $unit = new FactoryListContainer($factories);

        // ----------------------------------------------------------------
        // perform the change

        unset($unit['dummy']);

        // ----------------------------------------------------------------
        // test the results
    }

    /**
     * @covers ::__construct
     * @covers ::setReadWrite
     * @covers ::isReadOnly
     * @covers ::isReadWrite
     * @covers ::requireReadWrite
     */
    public function testCanPutIntoReadWriteMode()
    {
        // ----------------------------------------------------------------
        // setup your test

        $factories = [
            'dummy' => new FactoryListContainerTest_Builder
        ];
        $unit = new FactoryListContainer($factories);

        // ----------------------------------------------------------------
        // perform the change

        $unit->setReadWrite();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($unit->isReadOnly());
        $this->assertTrue($unit->isReadWrite());
    }

    public function provideNonArraysToTest()
    {
        return [
            [ null ],
            [ true ],
            [ false ],
            [ function() {} ],
            [ 0.0 ],
            [ 3.1415927 ],
            [ 0 ],
            [ 100 ],
            [ STDIN ],
            [ new \stdClass ],
            [ "hello, world!" ],
        ];
    }

    public function provideNonCallablesToTest()
    {
        return [
            [ null ],
            [ [] ],
            [ true ],
            [ false ],
            [ 0.0 ],
            [ 3.1415927 ],
            [ 0 ],
            [ 100 ],
            [ STDIN ],
            [ new \stdClass ],
            [ "hello, world!" ],
        ];
    }
}

class FactoryListContainerTest_Builder
{
    public function __invoke($arg1)
    {
        $retval = new InvalidArgumentException(__CLASS__ . ': ' . (string)$arg1);
        return $retval;
    }
}
