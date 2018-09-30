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

/**
 * Contract for a HealthReport.
 *
 * A HealthReport has a status (UP/DOWN) with a corresponding HTTP status code (200/503). Health reports with a DOWN
 * status can be enriched with a message.
 */
interface HealthReportInterface
{
    const STATUS_UP = 'UP';
    const STATUS_DOWN = 'DOWN';

    const STATUS_CODE_UP = 200;
    const STATUS_CODE_DOWN = 503;

    /**
     * @return HealthReportInterface
     */
    public static function buildStatusUp();

    /**
     * @return HealthReportInterface
     */
    public static function buildStatusDown($message = '');

    /**
     * @return bool
     */
    public function isDown();

    /**
     * @return int
     */
    public function getStatusCode();
}
