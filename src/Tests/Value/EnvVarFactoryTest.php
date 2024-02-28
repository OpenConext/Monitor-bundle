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
use OpenConext\MonitorBundle\Value\BuildEnvVarsFactory;
use OpenConext\MonitorBundle\Value\BuildInformation;
use PHPUnit\Framework\TestCase;

class EnvVarFactoryTest extends TestCase
{
    public function test_happy_flow()
    {
        $expectedSemverVersion = '1.1.0';
        $expectedRevision = 'e3f5a1f0';
        $expectedDate = '2024-01-01';
        $envVarVO = BuildEnvVarsFactory::buildFrom($expectedSemverVersion, $expectedRevision, $expectedDate);
        $this->assertInstanceOf(BuildInformation::class, $envVarVO);
        $this->assertInstanceOf(BuildEnvVars::class, $envVarVO);
        $this->assertTrue($envVarVO->hasRevision() && $envVarVO->hasVersion() && $envVarVO->hasCommitDate());
        $this->assertEquals($expectedSemverVersion, $envVarVO->getVersion());
        $this->assertEquals($expectedRevision, $envVarVO->getRevision());
        $this->assertEquals($expectedDate, $envVarVO->getCommitDate());
    }

    /**
     * @dataProvider buildValidVariants
     */
    public function test_happy_flow_variants(
        string $expectedSemverVersion,
        string $expectedRevision,
        ?string $expectedDate = null,
    ) {
        $envVarVO = BuildEnvVarsFactory::buildFrom($expectedSemverVersion, $expectedRevision, $expectedDate);
        $this->assertInstanceOf(BuildInformation::class, $envVarVO);
        $this->assertInstanceOf(BuildEnvVars::class, $envVarVO);
        $this->assertEquals($expectedSemverVersion, $envVarVO->getVersion());
        $this->assertEquals($expectedRevision, $envVarVO->getRevision());
        $this->assertEquals($expectedDate, $envVarVO->getCommitDate());
    }

    public function buildValidVariants()
    {
        return [
            'all set' => ['1.1.0', 'e97bbfc3', '2000-01-01'],
            'all set, long date' => ['1.1.0', 'e97bbfc3', '2000-01-01 00:00:00'],
            'no date' => ['1.1.0', 'e97bbfc3'],
            'full commit hash' => ['1.1.0', 'e97bbfc39653cb5541d453e704c64a7fab4f5b1'],
            'non-semver hash' => ['beta-1', 'e97bbfc39653cb5541d453e704c64a7fab4f5b1'],
        ];
    }

}
