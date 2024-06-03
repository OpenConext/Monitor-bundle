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

namespace OpenConext\MonitorBundle\HealthCheck;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\ConnectionException;
use Exception;
use OpenConext\MonitorBundle\Value\HealthReport;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * Test if there is a working database connection.
 *
 * This test is based on the Doctrine configuration. The default entity manager is injected (if configured) and is used
 * to perform a simple query on the configured connection.
 */
class DoctrineConnectionHealthCheck implements HealthCheckInterface
{

    public function __construct(
        #[Autowire(service: 'doctrine.dbal.default_connection')]
        private readonly ?Connection $connection,
    )
    {
    }

    public function check(HealthReportInterface $report): HealthReportInterface
    {
        // Skip database checking of no database is configured.
        if ($this->connection === null) {
            return $report;
        }

        try {
            // This will try to make a connection. It throws an exception if it fails on MySQL and Postgres.
            // In the case of SQLite it will create an empty database, so we need to check for empty tables later on.
            $this->connection->connect(); // This will create the SQLite database if it does not exist
            // Get the schema manager
            $sm = $this->connection->createSchemaManager();

            $tables = $sm->listTables();

            if ($tables === []) {
                return HealthReport::buildStatusDown('No tables found in the database.');
            }

            // Grab the first table to query on
            $table = reset($tables);
            // Perform a light-weight query on the chosen table
            $query = "SELECT * FROM %s LIMIT 1";
            $this->connection->executeQuery(sprintf($query, $table->getName()));

        } catch (ConnectionException) {
            return HealthReport::buildStatusDown('Unable to connect to the database.');
        } catch (Exception) {
            return HealthReport::buildStatusDown('Unable to execute a query on the database.');
        }

        return $report;
    }
}
