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
    use PrinterTrait;

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
}
