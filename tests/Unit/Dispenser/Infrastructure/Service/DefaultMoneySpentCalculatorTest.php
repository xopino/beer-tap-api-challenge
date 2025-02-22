<?php

namespace App\Tests\Unit\Dispenser\Infrastructure\Service;

use App\Dispenser\Infrastructure\Service\DefaultMoneySpentCalculator;
use PHPUnit\Framework\TestCase;

class DefaultMoneySpentCalculatorTest extends TestCase
{
    private DefaultMoneySpentCalculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new DefaultMoneySpentCalculator();
    }

    public function testCalculateReturnsCorrectValue(): void
    {
        // Given:
        // Tap opened at 12:00:00 and closed at 12:00:22 (22 seconds elapsed)
        // Flow volume is 0.064 litres/second.
        // Expected litres consumed = 0.064 * 22 = 1.408
        // Expected total spent = 1.408 * 12.25 = 17.248
        $startDate = '2022-01-01T12:00:00Z';
        $endDate   = '2022-01-01T12:00:22Z';
        $flowVolume = 0.064;
        $expectedSpent = 17.248;

        // When
        $result = $this->calculator->calculate($startDate, $endDate, $flowVolume);

        // Then
        $this->assertEquals($expectedSpent, $result);
    }

    public function testCalculateThrowsExceptionWhenStartEqualsEnd(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Start date and end date cannot be the same.');

        $startDate = '2022-01-01T12:00:00Z';
        $endDate   = '2022-01-01T12:00:00Z';
        $flowVolume = 0.064;

        $this->calculator->calculate($startDate, $endDate, $flowVolume);
    }

    public function testCalculateThrowsExceptionWhenEndBeforeStart(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('End date must be greater than start date.');

        $startDate = '2022-01-01T12:00:22Z';
        $endDate   = '2022-01-01T12:00:00Z';
        $flowVolume = 0.064;

        $this->calculator->calculate($startDate, $endDate, $flowVolume);
    }
}
