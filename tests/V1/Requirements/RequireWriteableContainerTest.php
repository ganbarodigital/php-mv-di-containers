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

namespace GanbaroDigitalTest\DIContainers\V1\Requirements;

use PHPUnit_Framework_Test;
use GanbaroDigital\DIContainers\V1\Exceptions\ContainerIsReadOnly;
use GanbaroDigital\DIContainers\V1\Exceptions\DIContainersExceptions;
use GanbaroDigital\DIContainers\V1\FactoryList\Containers\FactoryListContainer;
use GanbaroDigital\DIContainers\V1\Requirements\RequireWriteableContainer;
use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass GanbaroDigital\DIContainers\V1\Requirements\RequireWriteableContainer
 */
class RequireWriteableContainerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers ::apply
     * @covers ::__construct
     */
    public function test_can_instantiate()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $unit = RequireWriteableContainer::apply();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(RequireWriteableContainer::class, $unit);
    }

    /**
     * @covers ::apply
     * @covers ::to
     * @expectedException GanbaroDigital\DIContainers\V1\Exceptions\ContainerIsReadOnly
     */
    public function test_throws_exception_when_container_is_read_only()
    {
        // ----------------------------------------------------------------
        // setup your test

        $container = new FactoryListContainer(["hello" => [ContainerIsReadOnly::class, 'newFromVar']]);
        $container->setReadOnly();

        // ----------------------------------------------------------------
        // perform the change

        RequireWriteableContainer::apply()->to($container);

        // ----------------------------------------------------------------
        // test the results

    }

    /**
     * @covers ::apply
     * @covers ::to
     */
    public function test_does_not_throw_exception_when_container_is_read_write()
    {
        // ----------------------------------------------------------------
        // setup your test

        $container = new FactoryListContainer(["hello" => [ContainerIsReadOnly::class, 'newFromVar']]);
        $container->setReadWrite();

        // ----------------------------------------------------------------
        // perform the change

        RequireWriteableContainer::apply()->to($container);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($container->isReadWrite());
    }
}
