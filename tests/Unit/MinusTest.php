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
use Ixnode\PhpException\Type\TypeInvalidException;
use PHPUnit\Framework\TestCase;

/**
 * Class MinusTest
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-02-04)
 * @since 0.1.0 (2023-02-04) First version.
 */
final class MinusTest extends TestCase
{
    /**
     * Test wrapper.
     *
     * @dataProvider dataProviderMinus
     *
     * @test
     * @testdox $number) Test Minus of two given values
     * @param int $number
     * @param int|float $value1
     * @param int|float $value2
     * @param class-string<TypeInvalidException>|int|float $expected
     * @throws Exception
     */
    public function minus(int $number, int|float $value1, int|float $value2, string|int|float $expected): void
    {
        /* Arrange */
        if (is_string($expected)) {
            $this->expectException($expected);
        }

        /* Act */
        $sum = $value1 - $value2;

        /* Assert */
        $this->assertIsNumeric($number); // To avoid phpmd warning.
        $this->assertEquals($expected, $sum);
    }

    /**
     * Data provider.
     *
     * @return array<int, mixed>
     */
    public function dataProviderMinus(): array
    {
        $number = 0;

        return [
            [++$number, 0, 0, 0, ],
            [++$number, 1, 1, 0, ],
            [++$number, 1, 1, 0, ],
            [++$number, 1, 1, 0, ],
            [++$number, 1, 1, 0, ],
            [++$number, 1, 1, 0, ],
        ];
    }
}
