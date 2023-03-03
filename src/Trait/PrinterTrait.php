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

namespace Ixnode\PhpPhpunitPrinter\Trait;

use Ixnode\PhpException\Type\TypeInvalidException;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestResult;

/**
 * Trait PrinterTrait
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-03-03)
 * @since 0.1.0 (2023-03-03) First version.
 */
trait PrinterTrait
{
    private string $templateOutputDetail = '    (%%%dd/%%%dd) %%s %%-50s (%%.3fs)';

    private string $templateOverview = "  \033[01;36m%s\033[0m";

    private string $templateSuccess = "\033[01;32m%s\033[0m";

    private string $templateFailure = "\033[01;31m%s\033[0m";

    private string $statusTextError = "\033[01;31mE\033[0m";

    private string $signSuccess = "\x27\x14";

    private string $signFailure = "\x27\x16";

    private string $utf8 = 'UTF-8';

    private string $utf16be = 'UTF-16BE';

    private string $testTitleTest = 'test';

    private string $typeSuccess = '.';

    private string $typeError = 'E';

    private string $typeFailure = 'F';

    private string $typeFailureCoded = "\033[41;37mF\033[0m";

    private string $underline = '_';

    private string $space = ' ';

    protected string $testStatus = '';

    protected string $testName = '';

    protected string $testMessage = '';

    protected int $testCurrent = 0;

    protected int $testTotal = 0;

    /**
     * Prints the result. Overrides the parent::printResult method.
     *
     * @param TestResult $result
     */
    public function printResult(TestResult $result): void
    {
        print PHP_EOL;
        $this->printFooter($result);
        $this->printErrors($result);
        $this->printFailures($result);
        print PHP_EOL;
    }

    /**
     * Extends the parent::endTest method with some further information.
     *
     * @inheritdoc
     * @throws TypeInvalidException
     */
    public function endTest(Test $test, float $time): void
    {
        parent::endTest($test, $time);

        $this->writeProgressAfter($test, $time);

        $this->testCurrent++;
    }

    /**
     * Writes the current progress.
     *
     * @param Test $test
     * @param float $time
     * @return void
     * @throws TypeInvalidException
     */
    protected function writeProgressAfter(Test $test, float $time): void
    {
        $testName = $this->getTestName($test);

        if (is_null($testName)) {
            throw new TypeInvalidException('string', 'null');
        }

        $length = strlen(strval($this->testTotal));

        if ($this->testCurrent === 1) {
            print PHP_EOL;
        }

        echo sprintf(
            sprintf($this->templateOutputDetail, $length, $length).PHP_EOL,
            $this->testCurrent,
            $this->testTotal,
            $this->testStatus,
            $testName,
            $time
        );
    }

    /**
     * Extracts the test name from PHPUnit.
     *
     * @param Test $test
     * @return string|null
     */
    protected function getTestName(Test $test): string|null
    {
        if (!method_exists($test, 'getName')) {
            return null;
        }

        $testTitle = strval($this->testName ?: $test->getName());

        /* Removes the test prefix */
        if (str_starts_with($testTitle, $this->testTitleTest)) {
            $testTitle = substr($testTitle, 4);
        }

        /* Start with an uppercase sign. */
        $testTitle = ucfirst($testTitle);

        /* Replace _ with space to make the name more readable. */
        if (str_contains($testTitle, $this->underline)) {
            $testTitle = trim(str_replace($this->underline, $this->space, $testTitle));
        }

        return $testTitle;
    }

    /**
     * Starts the test suite. Overrides the parent::startTestSuite and print some additional headers.
     *
     * @inheritdoc
     * @param TestSuite $suite
     * @return void
     */
    public function startTestSuite(TestSuite $suite): void
    {
        $name = $suite->getName();

        /* Do not display empty names. */
        if (!empty($name)) {
            echo PHP_EOL;
            echo sprintf($this->templateOverview, $name).':';
            echo PHP_EOL;
        }

        /* Gets the total test count and initialize current count to 1. */
        $this->testTotal = $suite->count();
        $this->testCurrent = 1;

        parent::startTestSuite($suite);
    }

    /**
     * Replace the status with some cool icons.
     *
     * @param string $progress
     * @return string
     */
    protected function getStatusText(string $progress): string
    {
        return match ($progress) {
            $this->typeSuccess => sprintf($this->templateSuccess, mb_convert_encoding($this->signSuccess, $this->utf8, $this->utf16be)),
            $this->typeFailure, $this->typeFailureCoded => sprintf($this->templateFailure, mb_convert_encoding($this->signFailure, $this->utf8, $this->utf16be)),
            $this->typeError => $this->statusTextError,
            default => $progress,
        };
    }
}
