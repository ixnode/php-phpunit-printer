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

use DivisionByZeroError;
use Exception;
use Ixnode\PhpException\Type\TypeInvalidException;
use PHPUnit\Framework\TestCase;

/**
 * Class DivisionTest
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-02-04)
 * @since 0.1.0 (2023-02-04) First version.
 */
final class DivisionTest extends TestCase
{
    /**
     * Test wrapper.
     *
     * @dataProvider dataProviderDivision
     *
     * @test
     * @testdox $number) Test Division - $message
     * @param int $number
     * @param string $message
     * @param int|float $value1
     * @param int|float $value2
     * @param class-string<TypeInvalidException>|int|float $expected
     * @throws Exception
     */
    public function division(int $number, string $message, int|float $value1, int|float $value2, string|int|float $expected): void
    {
        /* Arrange */
        if (is_string($expected)) {
            $this->expectException($expected);
        }

        /* Act */
        $sum = $value1 / $value2;

        /* Assert */
        $this->assertIsNumeric($number); // To avoid phpmd warning.
        $this->assertIsString($message); // To avoid phpmd warning.
        $this->assertEquals($expected, $sum);
    }

    /**
     * Data provider.
     *
     * @return array<int, mixed>
     */
    public function dataProviderDivision(): array
    {
        $number = 0;

        return [
            [++$number, 'Division by 0', 0, 0, DivisionByZeroError::class, ],
            [++$number, 'Division 1 by 1', 1, 1, 1, ],
            [++$number, 'Division 2 by 1', 2, 1, 2, ],
            [++$number, 'Division 4 by 1', 4, 1, 4, ],
            [++$number, 'Division 8 by 2', 8, 2, 4, ],
        ];
    }
}
