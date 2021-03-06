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

final class MemprofExtensionNotLoadedException extends \RuntimeException implements Exception
{
}
