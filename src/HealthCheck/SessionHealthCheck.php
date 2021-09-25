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

use Exception;
use OpenConext\MonitorBundle\Value\HealthReport;

/**
 * Test the session status.
 *
 * This will test the existence of the session_start function, effectively testing if the session extension is
 * installed. It also validates sessions can be started by using the session_status & session_start functions.
 */
class SessionHealthCheck implements HealthCheckInterface
{
    public function check(HealthReportInterface $report): HealthReportInterface
    {
        if (!function_exists('session_start')) {
            return HealthReport::buildStatusDown(
                'session_start() must be available. Install and enable the session extension.'
            );
        }
        $sessionStatus = session_status();

        // Test session status
        if ($sessionStatus !== PHP_SESSION_DISABLED && $sessionStatus === PHP_SESSION_NONE) {
            try {
                session_start();
            } catch (Exception $e) {
                return HealthReport::buildStatusDown('Session support is enabled but no session could be started.');
            }
            // Destroy the session
            session_destroy();
        }
        return $report;
    }
}
