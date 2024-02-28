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

/**
 * Representation of the env vars that describe the build details
 *
 * By default, the following env vars are evaluated:
 *
 * OPENCONEXT_APP_VERSION | maps to $version    | required
 * OPENCONEXT_GIT_SHA     | maps to $revision   | required
 * OPENCONEXT_COMMIT_DATE | maps to $commitDate | optional
 *
 * This VO is created by its factory who actually reads the env-vars
 */
class BuildEnvVars implements BuildInformation
{
    public function __construct(
        private readonly string $version,
        private readonly string $revision,
        private readonly ?string $commitDate,
    ) {
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getRevision(): string
    {
        return $this->revision;
    }

    public function getCommitDate(): ?string
    {
        return $this->commitDate;
    }

    public function hasRevision(): bool
    {
        return true;
    }

    public function getPath(): string
    {
        return '';
    }

    public function hasVersion(): bool
    {
        return true;
    }

    public function hasPath(): bool
    {
        return false;
    }

    public function hasCommitDate(): bool
    {
        return !is_null($this->commitDate);
    }

    public function jsonSerialize(): mixed
    {
        $data = [
            'version' => $this->version,
            'revision' => $this->revision,
        ];
        if ($this->hasCommitDate()) {
            $data['commitDate'] = $this->commitDate;
        }

        return $data;
    }
}
