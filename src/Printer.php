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

use Ixnode\PhpException\Type\TypeInvalidException;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestResult;
use PHPUnit\TextUI\DefaultResultPrinter;

/**
 * Class Printer
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-02-04)
 * @since 0.1.0 (2023-02-04) First version.
 */
class Printer extends DefaultResultPrinter
{
    private const TEMPLATE_OUTPUT_DETAIL = '    (%%%dd/%%%dd) %%s %%-50s (%%.3fs)';

    private const TEMPLATE_OVERVIEW = "  \033[01;36m%s\033[0m";

    private const TEMPLATE_SUCCESS = "\033[01;32m%s\033[0m";

    private const TEMPLATE_FAILURE = "\033[01;31m%s\033[0m";

    private const STATUS_TEXT_ERROR = "\033[01;31mE\033[0m";

    private const SIGN_SUCCESS = "\x27\x14";

    private const SIGN_FAILURE = "\x27\x16";

    private const UTF_8 = 'UTF-8';

    private const UTF_16BE = 'UTF-16BE';

    private const TEST_TITLE_TEST = 'test';

    private const TYPE_SUCCESS = '.';

    private const TYPE_ERROR = 'E';

    private const TYPE_ASSERTION_FAILURE = 'F';

    private const TYPE_ASSERTION_FAILURE_CODED = "\033[41;37mF\033[0m";

    private const UNDERLINE = '_';

    private const SPACE = ' ';

    protected string $testStatus = '';

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
     * Overwrites the output from parent::writeProgress and saves the current status.
     *
     * @param string $progress
     * @return void
     */
    protected function writeProgress(string $progress): void
    {
        $this->testStatus = $this->getStatusText($progress);
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
            sprintf(self::TEMPLATE_OUTPUT_DETAIL, $length, $length).PHP_EOL,
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

        $testTitle = $test->getName();

        /* Removes the test prefix */
        if (str_starts_with($testTitle, self::TEST_TITLE_TEST)) {
            $testTitle = substr($testTitle, 4);
        }

        /* Start with an uppercase sign. */
        $testTitle = ucfirst($testTitle);

        /* Replace _ with space to make the name more readable. */
        if (str_contains($testTitle, self::UNDERLINE)) {
            $testTitle = trim(str_replace(self::UNDERLINE, self::SPACE, $testTitle));
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
            echo sprintf(self::TEMPLATE_OVERVIEW, $name).':';
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
            self::TYPE_SUCCESS => sprintf(self::TEMPLATE_SUCCESS, mb_convert_encoding(self::SIGN_SUCCESS, self::UTF_8, self::UTF_16BE)),
            self::TYPE_ASSERTION_FAILURE, self::TYPE_ASSERTION_FAILURE_CODED => sprintf(self::TEMPLATE_FAILURE, mb_convert_encoding(self::SIGN_FAILURE, self::UTF_8, self::UTF_16BE)),
            self::TYPE_ERROR => self::STATUS_TEXT_ERROR,
            default => $progress,
        };
    }
}
