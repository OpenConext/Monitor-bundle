<?php

/**
 * Copyright 2026 SURFnet B.V.
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

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\ConnectionException;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Table;
use Exception;
use Mockery as m;
use OpenConext\MonitorBundle\HealthCheck\DoctrineConnectionHealthCheck;
use OpenConext\MonitorBundle\Value\HealthReport;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class DoctrineConnectionHealthCheckTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
        parent::tearDown();
    }

    public function testSkipsCheckWhenConnectionIsNull(): void
    {
        $logger = m::mock(LoggerInterface::class);
        $logger->shouldNotReceive('error');

        $check = new DoctrineConnectionHealthCheck(null, $logger);
        $report = HealthReport::buildStatusUp();
        $result = $check->check($report);

        $this->assertFalse($result->isDown());
    }

    public function testLogsErrorOnConnectionException(): void
    {
        $exception = m::mock(ConnectionException::class);

        $connection = m::mock(Connection::class);
        $connection->shouldAllowMockingProtectedMethods();
        // Note: Connection::connect() is protected in Doctrine DBAL 4 and cannot be made to throw directly.
        // The exception is thrown from createSchemaManager() to exercise the ConnectionException catch block.
        $connection->shouldReceive('connect');
        $connection->shouldReceive('createSchemaManager')->andThrow($exception);

        $logger = m::mock(LoggerInterface::class);
        $logger->shouldReceive('error')
            ->once()
            ->with('Unable to connect to the database.', ['exception' => $exception]);

        $check = new DoctrineConnectionHealthCheck($connection, $logger);
        $result = $check->check(HealthReport::buildStatusUp());

        $this->assertTrue($result->isDown());
        $this->assertEquals('Unable to connect to the database.', $result->jsonSerialize()['message']);
    }

    public function testLogsErrorOnGenericException(): void
    {
        $exception = new Exception('Query failed');

        $connection = m::mock(Connection::class);
        $connection->shouldAllowMockingProtectedMethods();
        // Note: Connection::connect() is protected in Doctrine DBAL 4 and cannot be made to throw directly.
        // The exception is thrown from createSchemaManager() to exercise the generic Exception catch block.
        $connection->shouldReceive('connect');
        $connection->shouldReceive('createSchemaManager')->andThrow($exception);

        $logger = m::mock(LoggerInterface::class);
        $logger->shouldReceive('error')
            ->once()
            ->with('Unable to execute a query on the database.', ['exception' => $exception]);

        $check = new DoctrineConnectionHealthCheck($connection, $logger);
        $result = $check->check(HealthReport::buildStatusUp());

        $this->assertTrue($result->isDown());
        $this->assertEquals('Unable to execute a query on the database.', $result->jsonSerialize()['message']);
    }

    public function testReturnsUpReportWhenDatabaseIsHealthy(): void
    {
        $table = m::mock(Table::class);
        $table->shouldReceive('getName')->andReturn('some_table');

        $schemaManager = m::mock(AbstractSchemaManager::class);
        $schemaManager->shouldReceive('listTables')->andReturn([$table]);

        $connection = m::mock(Connection::class);
        $connection->shouldAllowMockingProtectedMethods();
        $connection->shouldReceive('connect');
        $connection->shouldReceive('createSchemaManager')->andReturn($schemaManager);
        $connection->shouldReceive('executeQuery');

        $logger = m::mock(LoggerInterface::class);
        $logger->shouldNotReceive('error');

        $report = HealthReport::buildStatusUp();
        $check = new DoctrineConnectionHealthCheck($connection, $logger);
        $result = $check->check($report);

        $this->assertSame($report, $result);
    }
}
