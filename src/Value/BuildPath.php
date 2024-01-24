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

use Webmozart\Assert\Assert;

/**
 * Representation of a BuildPath.
 *
 * Represents the path the application was installed in. The folder name contains version information which can be read
 * from this value object.
 */
class BuildPath implements BuildInformation
{
    public function __construct(
        private readonly string $path,
        private readonly string $version = '',
        private readonly string $revision = ''
    )
    {
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getRevision(): string
    {
        return $this->revision;
    }

    public function hasRevision(): bool
    {
        return trim($this->revision) !== '';
    }

    public function hasVersion(): bool
    {
        return trim($this->version) !== '';
    }

    public function getCommitDate(): string
    {
        return '';
    }

    public function hasPath(): bool
    {
        return trim($this->path) !== '';
    }

    public function hasCommitDate(): bool
    {
        return false;
    }

    public function jsonSerialize(): mixed
    {
        return $this->path;
    }
}
