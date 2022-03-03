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

namespace OpenConext\MonitorBundle\Controller;

use OpenConext\MonitorBundle\Value\BuildPathFactory;
use OpenConext\MonitorBundle\Value\Information;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Display specific information about the application.
 *
 * Information includes:
 *  - Name of the installation folder of the application
 *  - Symfony environment that is currently active
 *  - Debugger state
 *
 * And optionally (when able to retrieve them):
 *  - Build tag (semver version of installed release)
 *  - Commit revision of the installed release
 *  - Asset version (of the frontend assets)
 *
 * This data is returned in JSON format.
 */
class InfoController extends AbstractController
{
    /**
     * @var string
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
    private $systemInfo = [];

    public function __construct(
        $buildPath,
        $environment,
        $debuggerEnabled
    ) {
        $this->buildPath = $buildPath;
        $this->environment = $environment;
        $this->debuggerEnabled = $debuggerEnabled;

        if (function_exists('opcache_get_status')) {
            $this->systemInfo['opcache'] = opcache_get_status(false);
        }
    }

    public function __invoke(): JsonResponse
    {
        $info = Information::buildFrom(
            BuildPathFactory::buildFrom($this->buildPath),
            $this->environment,
            $this->debuggerEnabled,
            $this->systemInfo
        );

        return $this->json($info);
    }
}
