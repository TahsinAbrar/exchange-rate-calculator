<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{

    public function testFormatNumberValue()
    {
        $this->assertEquals(2.00, formatNumberValue(2));
        $this->assertEquals(2.00, formatNumberValue('2.00002'));
        $this->assertEquals(0.47, formatNumberValue('0.46854'));
        $this->assertNotEquals(0.47, formatNumberValue('0.46447'));
    }
}
