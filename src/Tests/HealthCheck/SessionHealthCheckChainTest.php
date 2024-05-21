<?php

/**
 * Copyright 2017 SURFnet B.V.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace OpenConext\MonitorBundle\Tests\HealthCheck;

use Mockery as m;
use OpenConext\MonitorBundle\HealthCheck\HealthCheckChain;
use OpenConext\MonitorBundle\HealthCheck\HealthCheckInterface;
use OpenConext\MonitorBundle\Value\HealthReport;
use PHPUnit\Framework\TestCase;

class SessionHealthCheckChainTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
        parent::tearDown();
    }

    public function testChain()
    {
        $statusOk = m::mock(HealthReport::buildStatusUp());
        $statusOk
            ->shouldReceive('isDown')
            ->andReturn(false);

        $checker1 = m::mock(HealthCheckInterface::class);
        $checker1
            ->shouldReceive('check')
            ->once()
            ->andReturn($statusOk);

        $checker2 = m::mock(HealthCheckInterface::class);
        $checker2
            ->shouldReceive('check')
            ->once()
            ->andReturn($statusOk);

        $checker3 = m::mock(HealthCheckInterface::class);
        $checker3
            ->shouldReceive('check')
            ->once()
            ->andReturn($statusOk);

        $iterator = new \ArrayIterator([$checker1, $checker2, $checker3]);

        $chain = new HealthCheckChain($iterator);

        $result = $chain->check();
        $this->assertEquals($statusOk, $result);
    }

    public function testChainStopsWhenCheckFails()
    {
        $statusOk = m::mock(HealthReport::buildStatusUp());
        $statusOk
            ->shouldReceive('isDown')
            ->andReturn(false);

        $statusDown = m::mock(HealthReport::buildStatusDown('Lorem ipsum dolor sit'));
        $statusOk
            ->shouldReceive('isDown')
            ->andReturn(true);

        $checker1 = m::mock(HealthCheckInterface::class);
        $checker1
            ->shouldReceive('check')
            ->once()
            ->andReturn($statusOk);

        $checker2 = m::mock(HealthCheckInterface::class);
        $checker2
            ->shouldReceive('check')
            ->once()
            ->andReturn($statusDown);

        $checker3 = m::mock(HealthCheckInterface::class);
        $checker3->shouldNotReceive('check');

        $iterator = new \ArrayIterator([$checker1, $checker2, $checker3]);

        $chain = new HealthCheckChain($iterator);

        $result = $chain->check();
        $this->assertEquals($statusDown, $result);
        $this->assertEquals('Lorem ipsum dolor sit', $result->jsonSerialize()['message']);
    }
}
