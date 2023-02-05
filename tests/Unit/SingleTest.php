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

namespace Ixnode\PhpPhpunitPrinter\Tests\Unit;

use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Class SingleTest
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-02-04)
 * @since 0.1.0 (2023-02-04) First version.
 */
final class SingleTest extends TestCase
{
    /**
     * Test wrapper.
     *
     * @test
     * @throws Exception
     */
    public function single(): void
    {
        /* Arrange */
        $value1 = 1;
        $value2 = 1;
        $expected = 2;

        /* Act */
        $sum = $value1 + $value2;

        /* Assert */
        $this->assertEquals($expected, $sum);
    }
}
