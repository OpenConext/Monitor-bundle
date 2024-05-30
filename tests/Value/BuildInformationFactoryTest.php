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

namespace OpenConext\MonitorBundle\Tests\Value;

use OpenConext\MonitorBundle\Value\BuildEnvVars;
use OpenConext\MonitorBundle\Value\BuildInformationFactory;
use OpenConext\MonitorBundle\Value\BuildPath;
use PHPUnit\Framework\TestCase;

class BuildInformationFactoryTest extends TestCase
{
    public function test_builds_based_on_env_vars()
    {
        $buildInformation = BuildInformationFactory::build(
            '1.0.0',
            'e97bbfc39653cb5541d453e704c64a7fab4f5b1',
            '1900-01-01',
            'My-Application-da39a3ee5e6b4b0d3255bfef95601890afd80709'
        );
        $this->assertInstanceOf(BuildEnvVars::class, $buildInformation);
    }
    public function test_builds_based_on_env_vars_commit_date_optional()
    {
        $buildInformation = BuildInformationFactory::build(
            '1.0.0',
            'e97bbfc39653cb5541d453e704c64a7fab4f5b1',
            null,
            'My-Application-da39a3ee5e6b4b0d3255bfef95601890afd80709'
        );
        $this->assertInstanceOf(BuildEnvVars::class, $buildInformation);
    }
    public function test_builds_on_path()
    {
        $buildInformation = BuildInformationFactory::build(
            null,
            null,
            null,
            'My-Application-da39a3ee5e6b4b0d3255bfef95601890afd80709'
        );
        $this->assertInstanceOf(BuildPath::class, $buildInformation);
    }
}
