<?php

namespace App\Tests\Unit\Dispenser\Infrastructure\Service;

use App\Dispenser\Infrastructure\Service\DefaultMoneySpentCalculator;
use PHPUnit\Framework\TestCase;

class DefaultMoneySpentCalculatorTest extends TestCase
{
    private DefaultMoneySpentCalculator $classUnderTest;

    public function setUp(): void
    {
        parent::setUp();

        $this->classUnderTest = new DefaultMoneySpentCalculator();
    }

    /**
     * @dataProvider provideCalculationData
     */
    public function testCalculate(string $startDate, string $endDate, float $flowVolume, float $price, float $expected): void
    {
        $result = $this->classUnderTest->calculate($startDate, $endDate, $flowVolume, $price);
        $this->assertEquals($expected, $result, '', 0.01);
    }

    public static function provideCalculationData(): array
    {
        return [
            '1 second at 1L/s, price 1'     => ['2024-02-21 12:00:00', '2024-02-21 12:00:01', 1.0, 1.0, 1.0],
            '10 seconds at 2L/s, price 0.5' => ['2024-02-21 12:00:00', '2024-02-21 12:00:10', 2.0, 0.5, 10.0],
            '30 seconds at 0.5L/s, price 2' => ['2024-02-21 12:00:00', '2024-02-21 12:00:30', 0.5, 2.0, 30.0],
            '5 minutes at 3L/s, price 0.75' => ['2024-02-21 12:00:00', '2024-02-21 12:05:00', 3.0, 0.75, 675.0],
        ];
    }

    /**
     * @dataProvider provideInvalidDates
     */
    public function testCalculateThrowsExceptionOnInvalidDates(string $startDate, string $endDate): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/Start date and end date cannot be the same|End date must be greater than start date/');

        $this->classUnderTest->calculate($startDate, $endDate, 1.0, 1.0);
    }

    public static function provideInvalidDates(): array
    {
        return [
            'Same start and end date'      => ['2024-02-21 12:00:00', '2024-02-21 12:00:00'],
            'End date before start date'   => ['2024-02-21 12:01:00', '2024-02-21 12:00:00'],
        ];
    }
}
