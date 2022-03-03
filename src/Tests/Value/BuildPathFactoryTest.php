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

namespace OpenConext\MonitorBundle\Tests\Value;

use OpenConext\MonitorBundle\Value\BuildPath;
use OpenConext\MonitorBundle\Value\BuildPathFactory;
use PHPUnit\Framework\TestCase;

class BuildPathFactoryTest extends TestCase
{
    /**
     * @dataProvider semverBuildPaths
     */
    public function testItExtractsSemverFromBuildPath($path, $expectedSemverVersion)
    {
        $buildPath = BuildPathFactory::buildFrom($path);
        $this->assertInstanceOf(BuildPath::class, $buildPath);
        $this->assertEquals($expectedSemverVersion, $buildPath->getVersion());
        $this->assertEquals($path, $buildPath->getPath());
    }
    /**
     * @dataProvider revisionBuildPaths
     */
    public function testItExtractsRevisionFromBuildPath($path, $expectedRevision)
    {
        $buildPath = BuildPathFactory::buildFrom($path);
        $this->assertInstanceOf(BuildPath::class, $buildPath);
        $this->assertEquals($expectedRevision, $buildPath->getRevision());
        $this->assertEquals($path, $buildPath->getPath());
    }

    public function semverBuildPaths(): array
    {
        return [
            ['Stepup-Gateway-2.2.0-20161018092553Z-bc6bbf8e2006d15cbe883d8045724cdb1166e759', '2.2.0'],
            ['OpenConext-profile-0.4.0', '0.4.0'],
            ['My-Application-1.18.24-stable-derived-from-1.18.23', '1.18.24'],
            ['My-Application-1.3.beta-test', ''],
        ];
    }

    public function revisionBuildPaths(): array
    {
        return [
            ['Stepup-Gateway-2.2.0-20161018092553Z-bc6bbf8e2006d15cbe883d8045724cdb1166e759', 'bc6bbf8'],
            ['My-Application-da39a3ee5e6b4b0d3255bfef95601890afd80709', 'da39a3e'],
            ['63b50d1a23fb5c04ef2553829471d4a6c0220ce0', '63b50d1'],
            ['63b50d1a23fb5c04ef2553829471d4a6c0220ce', ''],
            ['Stepup-Gateway-2.2.0-20161018092553Z-15cbe88', ''],
        ];
    }
}
