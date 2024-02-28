<?php

/**
 * Copyright 2024 SURFnet B.V.
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

use function is_string;

class BuildInformationFactory
{
    public static function build(
        ?string $version,
        ?string $revision,
        ?string $commitDate,
        string $buildPath,
    ): BuildInformation
    {
        if (is_string($revision) && is_string($version)) {
            return BuildEnvVarsFactory::buildFrom($version, $revision, $commitDate);
        }
        return BuildPathFactory::buildFrom($buildPath);
    }
}
