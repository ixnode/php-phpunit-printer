<?php

/*
 * This file is part of the ixnode/php-phpunit-printer project.
 *
 * (c) Björn Hempel <https://www.hempel.li/>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Ixnode\PhpPhpunitPrinter;

use Ixnode\PhpPhpunitPrinter\Trait\PrinterTrait;
use PHPUnit\Runner\PhptTestCase;
use PHPUnit\Util\Color;
use PHPUnit\Util\TestDox\CliTestDoxPrinter;

/**
 * Class Printer
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-02-04)
 * @since 0.1.0 (2023-02-04) First version.
 */
class TestDoxPrinter extends CliTestDoxPrinter
{
    use PrinterTrait;

    /**
     * Overwrites the output from parent::writeProgress and saves the current status.
     *
     * @param string $progress
     * @return void
     */
    public function writeProgress(string $progress): void
    {
        parent::writeProgress($progress);

        $this->testStatus = $this->getStatusText($progress);
    }

    /**
     * Saves the testdox name.
     *
     * @param array<string, mixed> $prevResult
     * @param array<string, mixed> $result
     * @return void
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function writeTestResult(array $prevResult, array $result): void
    {
        $testName = strval($result['testMethod']);

        /* Gets the test name. */
        if ($this->colors && strval($result['className']) === PhptTestCase::class) {
            $testName = Color::colorizePath(strval($result['testName']), strval($prevResult['testName']), true);
        }

        $this->testName = $testName;
        $this->testMessage = strval($result['message']);
    }
}
