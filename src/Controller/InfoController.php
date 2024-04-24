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

use OpenConext\MonitorBundle\Value\BuildInformationFactory;
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
    private array $systemInfo = [];

    public function __construct(
        private readonly string $buildPath,
        private readonly string $environment,
        private readonly bool $debuggerEnabled,
        private readonly ?string $version,
        private readonly ?string $revision,
        private readonly ?string $commitDate,
    ) {

        if (function_exists('opcache_get_status')) {
            $this->systemInfo['opcache'] = opcache_get_status(false);
        }
    }

    public function __invoke(): JsonResponse
    {
        $buildInformation = BuildInformationFactory::build(
            $this->version,
            $this->revision,
            $this->commitDate,
            $this->buildPath
        );
        $info = Information::buildFrom(
            $buildInformation,
            $this->environment,
            $this->debuggerEnabled,
            $this->systemInfo
        );

        return $this->json($info);
    }
}
