<?php declare(strict_types=1);
/*
 * This file is part of the phpunit-memprof-listener.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnit\MemoryProfiler;

use PHPUnit\Framework\TestListener as TestListenerInterface;
use PHPUnit\Framework\TestListenerDefaultImplementation;
use PHPUnit\Framework\TestSuite;

final class TestListener implements TestListenerInterface
{
    use TestListenerDefaultImplementation;

    /**
     * @var int
     */
    private $testSuites = 0;

    /**
     * @var string
     */
    private $output;

    /**
     * @var string
     */
    private $format;

    /**
     * @var bool
     */
    private $profilingWasNotEnabledOnProcessStartup = false;

    /**
     * @throws InvalidTargetDirectoryException
     * @throws MemprofExtensionNotLoadedException
     * @throws UnsupportedDumpFormatException
     */
    public function __construct(string $targetDirectory = '/tmp', string $format = 'callgrind')
    {
        $this->ensureDumpFormatIsValid($format);
        $this->ensureTargetDirectoryIsWritable($targetDirectory);
        $this->ensureProfilerIsAvailable();

        $this->output = \fopen($targetDirectory . DIRECTORY_SEPARATOR . \uniqid('phpunit_memprof_'), 'wb');
        $this->format = $format;
    }

    public function startTestSuite(TestSuite $suite): void
    {
        if ($this->testSuites === 0 && !\memprof_enabled()) {
            \memprof_enable();

            $this->profilingWasNotEnabledOnProcessStartup = true;
        }

        $this->testSuites++;
    }

    public function endTestSuite(TestSuite $suite): void
    {
        $this->testSuites--;

        if ($this->testSuites === 0) {
            switch ($this->format) {
                case 'callgrind':
                    \memprof_dump_callgrind($this->output);
                    break;

                case 'pprof':
                    \memprof_dump_pprof($this->output);
                    break;
            }

            if ($this->profilingWasNotEnabledOnProcessStartup) {
                \memprof_disable();
            }
        }
    }

    /**
     * @throws UnsupportedDumpFormatException
     */
    private function ensureDumpFormatIsValid(string $format): void
    {
        switch ($format) {
            case 'callgrind':
            case 'pprof':
                return;

            default:
                throw new UnsupportedDumpFormatException;
        }
    }

    /**
     * @throws MemprofExtensionNotLoadedException
     */
    private function ensureProfilerIsAvailable(): void
    {
        if (!\extension_loaded('memprof')) {
            throw new MemprofExtensionNotLoadedException;
        }
    }

    /**
     * @throws InvalidTargetDirectoryException
     */
    private function ensureTargetDirectoryIsWritable(string $directory): void
    {
        if (!@\mkdir($directory) && !\is_dir($directory)) {
            throw new InvalidTargetDirectoryException;
        }
    }
}
