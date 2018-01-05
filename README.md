# phpunit-memprof-listener

Test Listener for [PHPUnit](https://github.com/sebastianbergmann/phpunit/) that uses the [memprof](https://github.com/arnaud-lb/php-memory-profiler) extension to dump memory profile information.

## Installation

You can add this library as a local, per-project dependency to your project using [Composer](https://getcomposer.org/):

    composer require phpunit/phpunit-memprof-listener

If you only need this library during development, for instance to run your project's test suite, then you should add it as a development-time dependency:

    composer require --dev phpunit/phpunit-memprof-listener

## Usage

...

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
