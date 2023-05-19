<?php

namespace Tests\Unit;

use App\Services\CurrencyService;
use PHPUnit\Framework\TestCase;

class CurrencyTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_convert_usd_to_eur_successful()
    {
        $result = (new CurrencyService())->convert(150, 'usd', 'eur');

        $this->assertEquals(147, $result);
    }

    public function test_convert_usd_to_gbp_successful()
    {
        $result = (new CurrencyService())->convert(150, 'usd', 'gbp');

        $this->assertEquals(0, $result);
    }
}
