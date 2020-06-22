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

namespace OpenConext\MonitorBundle\Value;

use JsonSerializable;
use OpenConext\MonitorBundle\HealthCheck\HealthReportInterface;

/**
 * Representation of a HealthReport.
 */
class HealthReport implements HealthReportInterface, JsonSerializable
{
    /**
     * @var string
     */
    private $status;

    /**
     * @var int
     */
    private $code;

    /**
     * @var string
     */
    private $message = '';

    public static function buildStatusUp()
    {
        return new self(HealthReportInterface::STATUS_UP, HealthReportInterface::STATUS_CODE_UP);
    }

    public static function buildStatusDown($message = '')
    {
        return new self(HealthReportInterface::STATUS_DOWN, HealthReportInterface::STATUS_CODE_DOWN, $message);
    }

    /**
     * @param string $status
     * @param string $message optional
     */
    private function __construct($status, $code, $message = '')
    {
        $this->status = $status;
        $this->code = $code;
        $this->message = $message;
    }

    public function isDown()
    {
        return $this->status === HealthReportInterface::STATUS_DOWN;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->code;
    }

    public function jsonSerialize()
    {
        $report = [
            'status' => $this->status
        ];
        if (trim($this->message) !== '') {
            $report['message'] = $this->message;
        }

        return $report;
    }
}
