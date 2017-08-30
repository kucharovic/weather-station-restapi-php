<?php

namespace App\Tests\Doctrine\Type;

use PHPUnit\Framework\TestCase;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use App\Doctrine\Type\DateTimeType;
use App\Utils\DateTime;

class DateTimeTypeTest extends TestCase
{
    public function setUp()
    {
        $this->type = Type::getType('mydatetime');
        $this->platform = $this->getMockBuilder(AbstractPlatform::class)->getMock();
    }

    public function testConvertToPHPValue()
    {
        $date = $this->type->convertToPHPValue('2017-08-23 00:07:00', $this->platform);
        $this->assertInstanceOf(DateTime::class, $date);
        $this->assertEquals('2017-08-23T00:07:00+02:00', strval($date));
    }
}