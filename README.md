# OpenConext Monitor bundle
[![Build Status](https://travis-ci.org/OpenConext/Monitor-bundle.svg)](https://travis-ci.org/OpenConext/Monitor-bundle) 

A Symfony2 bundle that adds a /health and /info endpoint to your application.

The endpoints return JSON responses. The `/info` endpoint tries to give as much information about the currently installed 
version of the application as possible. This information is based on the build path of the installation. But also
includes the Symfony environment that is currently active and whether or not the debugger is enabled.

The `/health` endpoint reports on the health of the application. This information could be used for example by a load
balancer. Example output:

```json
{"status":"UP"}
``` 

When a health check failed the HTTP Response status code will be 503. And the JSON Response is formatted like this: 
```json
{"status":"DOWN", "message":"Lorem ipsum dolor sit"}
``` 

:exclamation: Please note that only the first failing health check is reported.


## Installation

 * Add the package to your Composer file
    ```sh
    composer require openconext/monitor-bundle
    ```

 * Add the bundle to your kernel in `app/AppKernel.php`
    ```php
    public function registerBundles()
    {
        // ...
        new OpenConext\MonitorBundle\OpenConextMonitorBundle(),
    }
    ```
 * Include the routing configuration in `app/config/routing.yml` by adding:
    ```yaml
    openconext_monitor:
        resource:   "@OpenConextMonitorBundle/Resources/config/routing.yml"
        prefix:     /
     ```
 
 * Add security exceptions in `app/config/security.yml` (if this is required at all)
    ```yaml
    security:
        firewalls:
            monitor:
                pattern: ^/(info|health)$
                security: false

    ```
 * The /info and /health endpoints should now be available for everybody. Applying custom access restriction is up to
    the implementer of this bundle. 
    
## Adding Health Checks
The Monitor ships with two health checks. These checks are a
 - Database connection check based on Doctrine configuration
 - Session status check
 
### Create the checker
A `HealthCheckInterface` can be implemented to create your own health check. The example below shows an example of what
an implementation of said interface could look like.

```php
use OpenConext\MonitorBundle\HealthCheck\HealthCheckInterface;
use OpenConext\MonitorBundle\HealthCheck\HealthReportInterface;
use OpenConext\MonitorBundle\Value\HealthReport;

class ApiHealthCheck implements HealthCheckInterface
{
    /**
     * @var MyService
     */
    private $testService;

    public function __construct(MyService $service)
    {
        $this->testService = $service;
    }

    /**
     * @param HealthReportInterface $report
     * @return HealthReportInterface
     */
    public function check(HealthReportInterface $report)
    {
        if (!$this->testService->everythingOk()) {
            // Return a HealthReport with a DOWN status when there are indications the application is not functioning as
            // intended. You can provide an optional message that is displayed alongside the DOWN status.
            return HealthReport::buildStatusDown('Not everything is allright.');
        }
        // By default return the report that was passed along as a parameter to the check method
        return $report;
    }
}
``` 
:exclamation: Please note that the check method receives and returns a HealthReport. The Health report is passed along in the chain of
registered health checkers. If everything was OK, just return the report that was passed to the method. 

### Register the checker
To actually include the home made checker simply tag it as being a

Example service definition in `services.yml`

```yaml
services:
    acme.monitor.my_custom_health_check:
        class: Acme\AppBundle\HealthCheck\MyCustomHealthCheck
        arguments:
            - @test_service
        tags:
            - { name: surfnet.monitor.health_check }
```