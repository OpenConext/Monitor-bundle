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

use OpenConext\MonitorBundle\HealthCheck\HealthCheckChain;
use OpenConext\MonitorBundle\Value\HealthReport;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Display the health state of the application.
 *
 * The health controller is used to display the health of the application. Information is returned as a JSON response.
 * When one of the health checks (run by the HealthCheckerChain) fails, the DOWN message of that check is shown.
 */
class HealthController extends AbstractController
{
    public function __construct(
        private readonly HealthCheckChain $healthChecker
    ) {
    }

    #[Route('/health', name: 'monitor.health', methods: ['GET'])]
    #[Route('/internal/health', name: 'monitor.internal_health', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        try {
            $statusResponse = $this->healthChecker->check();
        } catch (\Exception $exception) {
            $statusResponse = HealthReport::buildStatusDown($exception->getMessage());
        }
        return $this->json($statusResponse, $statusResponse->getStatusCode());
    }
}
