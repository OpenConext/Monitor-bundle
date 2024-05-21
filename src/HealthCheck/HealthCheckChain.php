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

use OpenConext\MonitorBundle\Value\HealthReport;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

/**
 * Collect HealthCheck instances and checks them for UP or DOWN status.
 */
class HealthCheckChain
{
    /**
     * @var HealthCheckInterface[]
     */
    private $checks;

    public function __construct(
        #[TaggedIterator('openconext.monitor.health_check')] private readonly iterable $healthChecks
    )
    {
        $this->checks = [];

        foreach ($healthChecks as $healthCheck) {
            $this->addHealthCheck($healthCheck);
        }
    }

    public function addHealthCheck(HealthCheckInterface $healthCheck)
    {
        $this->checks[] = $healthCheck;
    }

    /**
     * Checks all registered HealthCheckers and stops on the first encounter of a failing test.
     */
    public function check(): HealthReportInterface
    {
        $report = HealthReport::buildStatusUp();
        if (!empty($this->checks)) {
            foreach ($this->checks as $check) {
                $report = $check->check($report);
                if ($report->isDown()) {
                    return $report;
                }
            }
        }
        return $report;
    }
}
