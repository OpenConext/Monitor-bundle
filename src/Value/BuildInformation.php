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

use JsonSerializable;

interface BuildInformation extends JsonSerializable
{
    public function getVersion(): string;

    public function jsonSerialize(): mixed;

    public function getRevision(): string;

    public function hasRevision(): bool;

    public function getPath(): string;

    public function getCommitDate(): ?string;

    public function hasVersion(): bool;

    public function hasPath(): bool;

    public function hasCommitDate(): bool;

}
