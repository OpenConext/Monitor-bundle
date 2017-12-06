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

namespace OpenConext\MonitorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Register services tagged as HealthCheck checker.
 */
class HealthCheckPass implements CompilerPassInterface
{
    /**
     * @SuppressWarnings(PHPMD.UnusedLocalVariable) $tags is never used in the foreach
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('openconext.monitor.health_check_chain')) {
            return;
        }

        $definition = $container->findDefinition('openconext.monitor.health_check_chain');

        // find all service IDs with the app.mail_transport tag
        $taggedServices = $container->findTaggedServiceIds('openconext.monitor.health_check');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addHealthCheck', array(new Reference($id)));
        }
    }
}
