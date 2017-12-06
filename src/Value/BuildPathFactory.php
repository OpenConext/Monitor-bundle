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

/**
 * Build instances of BuildPath.
 *
 * Builds BuildPath objects from a folder name. The folder name is inspected for the inclusion of a semver version
 * number and a commit revision hash.
 */
class BuildPathFactory
{
    /**
     * Regex used to test a string for the inclusion of a semver version number.
     * @var string
     */
    private static $semverRegex = '/\d+\.\d+\.\d+/';

    /**
     * Regex used to test a string for the inclusion of a sha1 revision number.
     * @var string
     */
    private static $revisionRegex = '/\b([a-f0-9]{40})\b/';

    /**
     * @param string $path
     * @return BuildPath
     */
    public static function buildFrom($path)
    {
        $version = '';
        $revision = '';

        if (self::pathHasSemVer($path)) {
            $version = self::getVersionFrom($path);
        }

        if (self::pathHasRevisionNumber($path)) {
            $revision = self::getRevisionFrom($path);
        }

        return new BuildPath($path, $version, $revision);
    }

    private static function pathHasSemVer($path)
    {
        return preg_match(self::$semverRegex, $path) !== 0;
    }

    /**
     * Returns the first match of a semver version number in the path.
     * @param $path
     * @return mixed
     */
    private static function getVersionFrom($path)
    {
        $matches = [];
        preg_match(self::$semverRegex, $path, $matches);

        return array_shift($matches);
    }

    private static function pathHasRevisionNumber($path)
    {
        return preg_match(self::$revisionRegex, $path) !== 0;
    }

    /**
     * Returns the short representation of a revision number bc6bbf8e2006d15cbe883d8045724cdb1166e759 is turned into
     * bc6bbf8
     *
     * @param $path
     * @return bool|string
     */
    private static function getRevisionFrom($path)
    {
        $matches = [];
        preg_match(self::$revisionRegex, $path, $matches);

        $match = array_shift($matches);
        return substr($match, 0, 7);
    }
}
