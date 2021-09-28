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
use Webmozart\Assert\Assert;

/**
 * Representation of build information.
 */
class Information implements JsonSerializable
{
    /**
     * @var BuildPath
     */
    private $buildPath;

    /**
     * @var string
     */
    private $environment;

    /**
     * @var bool
     */
    private $debuggerEnabled;

    /**
     * @var array
     */
    private $systemInfo;

    /**
     * @param BuildPath $buildPath
     * @param $environment
     * @param $debuggerEnabled
     * @return Information
     */
    public static function buildFrom(BuildPath $buildPath, $environment, $debuggerEnabled, array $systemInfo)
    {
        Assert::stringNotEmpty($environment, 'Environment must have a non empty string value');
        Assert::boolean($debuggerEnabled, 'Debugger enabled must have a boolean value');

        return new self($buildPath, $environment, $debuggerEnabled, $systemInfo);
    }

    /**
     * @param BuildPath $buildPath
     * @param string $environment
     * @param bool $debuggerEnabled
     */
    public function __construct(BuildPath $buildPath, $environment, $debuggerEnabled, array $systemInfo)
    {
        $this->buildPath = $buildPath;
        $this->environment = $environment;
        $this->debuggerEnabled = $debuggerEnabled;
        $this->systemInfo = $systemInfo;
    }

    public function jsonSerialize()
    {
        $information = [
            'build' => $this->buildPath->getPath(),
            'env' => $this->environment,
            'debug' => $this->debuggerEnabled,
            'system' => $this->systemInfo,
        ];

        if ($this->buildPath->hasRevision()) {
            $information['revision'] = $this->buildPath->getRevision();
        }

        if ($this->buildPath->hasVersion()) {
            $information['version'] = $this->buildPath->getVersion();
        }

        return $information;
    }
}
