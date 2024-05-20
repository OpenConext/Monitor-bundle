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

use Doctrine\ORM\EntityManager;
use Exception;
use OpenConext\MonitorBundle\Value\HealthReport;

/**
 * Test if there is a working database connection.
 *
 * This test is based on the Doctrine configuration. The default entity manager is injected (if configured) and is used
 * to perform a simple query on the configured connection.
 */
class DoctrineConnectionHealthCheck implements HealthCheckInterface
{
    /**
     * @var EntityManager|null
     */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function check(HealthReportInterface $report): HealthReportInterface
    {
        // Was the entityManager injected? When it is not the project does not use Doctrine ORM
        if (!is_null($this->entityManager)) {
            try {
                // Get the schema manager and grab the first table to later query on
                $sm = $this->entityManager->getConnection()->createSchemaManager();
                $tables = $sm->listTables();
                if (!empty($tables)) {
                    $table = reset($tables);
                    // Perform a light-weight query on the chosen table
                    $query = 'SELECT * FROM `%s` LIMIT 1';
                    $this->entityManager->getConnection()->executeQuery(sprintf($query, $table->getName()));
                }
            } catch (Exception $e) {
                return HealthReport::buildStatusDown('Unable to execute a query on the database.');
            }
        }
        return $report;
    }
}
