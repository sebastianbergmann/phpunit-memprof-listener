# phpunit-memprof-listener

Test Listener for [PHPUnit](https://github.com/sebastianbergmann/phpunit/) that uses the [memprof](https://github.com/arnaud-lb/php-memory-profiler) extension to dump memory profile information.

## Installation

You can add this library as a local, per-project, development-time dependency to your project using [Composer](https://getcomposer.org/):

    composer require --dev phpunit/phpunit-memprof-listener

## Usage

The example below shows how you activate and configure this test listener in your PHPUnit XML configuration file:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/6.5/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         forceCoversAnnotation="true"
         beStrictAboutCoversAnnotation="true"
         beStrictAboutOutputDuringTests="true"
         beStrictAboutTodoAnnotatedTests="true"
         verbose="true">
    <testsuite>
        <directory suffix="Test.php">tests</directory>
    </testsuite>

    <filter>
        <whitelist processUncoveredFilesFromWhitelist="true">
            <directory suffix=".php">src</directory>
        </whitelist>
    </filter>

    <listeners>
        <listener class="PHPUnit\MemoryProfiler\TestListener">
            <arguments>
                <string>/tmp</string>
                <string>callgrind</string>
            </arguments>
        </listener>
    </listeners>
</phpunit>
```

The following elements are relevant to this test listener and its configuration:

* `<listeners>` is the configuration section for test listeners
* `<listener>` configures (an instance of) the `PHPUnit\MemoryProfiler\TestListener` class as a test listener
* `<arguments>` is the configuration for that test listener
* The first argument is the path to the directory where the memory profile information is to be dumped, in this example `/tmp`
* The second argument is the desired format for the memory profile information dump, in this example `callgrind` (valid values are `callgrind` and `pprof`)

The rest of the `phpunit.xml` example shown above are best practice configuration defaults that were generated using `phpunit --generate-configuration`.

