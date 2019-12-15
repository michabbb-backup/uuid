<?php

namespace Ramsey\Uuid\Test\Builder;

use PHPUnit\Framework\MockObject\MockObject;
use Ramsey\Uuid\Builder\DefaultUuidBuilder;
use Ramsey\Uuid\Codec\CodecInterface;
use Ramsey\Uuid\Converter\NumberConverterInterface;
use Ramsey\Uuid\Converter\TimeConverterInterface;
use Ramsey\Uuid\Test\TestCase;
use Ramsey\Uuid\Uuid;

class DefaultUuidBuilderTest extends TestCase
{
    public function testBuildCreatesUuid(): void
    {
        /** @var MockObject & NumberConverterInterface $numberConverter */
        $numberConverter = $this->getMockBuilder(NumberConverterInterface::class)->getMock();

        /** @var MockObject & TimeConverterInterface $timeConverter */
        $timeConverter = $this->getMockBuilder(TimeConverterInterface::class)->getMock();

        $builder = new DefaultUuidBuilder($numberConverter, $timeConverter);

        /** @var MockObject & CodecInterface $codec */
        $codec = $this->getMockBuilder(CodecInterface::class)->getMock();

        $fields = [
            'time_low' => '754cd475',
            'time_mid' => '7e58',
            'time_hi_and_version' => '5411',
            'clock_seq_hi_and_reserved' => '73',
            'clock_seq_low' => '22',
            'node' => 'be0725c8ce01'
        ];

        $result = $builder->build($codec, $fields);
        $this->assertInstanceOf(Uuid::class, $result);
    }
}
