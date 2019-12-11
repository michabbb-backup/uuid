<?php
namespace Ramsey\Uuid\Test\Encoder;

use PHPUnit\Framework\MockObject\MockObject;
use Ramsey\Uuid\Builder\UuidBuilderInterface;
use Ramsey\Uuid\Codec\CodecInterface;
use Ramsey\Uuid\Codec\TimestampLastCombCodec;
use Ramsey\Uuid\Test\TestCase;
use Ramsey\Uuid\UuidInterface;

class TimestampLastCombCodecTest extends TestCase
{
    /**
     * @var CodecInterface
     */
    private $codec;

    /**
     * @var MockObject
     */
    private $builderMock;

    protected function setUp(): void
    {
        $this->builderMock = $this->getMockBuilder(UuidBuilderInterface::class)->getMock();
        $this->codec = new TimestampLastCombCodec($this->builderMock);
    }

    public function testEncoding()
    {
        $uuidMock = $this->getMockBuilder(UuidInterface::class)->getMock();
        $uuidMock->expects($this->any())
            ->method('getFieldsHex')
            ->willReturn(['0800200c', '9a66', '11e1', '9b', '21', 'ff6f8cb0c57d']);
        $encodedUuid = $this->codec->encode($uuidMock);

        $this->assertSame('0800200c-9a66-11e1-9b21-ff6f8cb0c57d', $encodedUuid);
    }

    public function testBinaryEncoding()
    {
        $uuidMock = $this->getMockBuilder(UuidInterface::class)->getMock();
        $uuidMock->expects($this->any())
            ->method('getHex')
            ->willReturn('0800200c9a6611e19b21ff6f8cb0c57d');
        $encodedUuid = $this->codec->encodeBinary($uuidMock);

        $this->assertSame(hex2bin('0800200c9a6611e19b21ff6f8cb0c57d'), $encodedUuid);
    }

    public function testDecoding()
    {
        $this->builderMock->expects($this->exactly(1))
            ->method('build')
            ->with(
                $this->codec,
                [
                    'time_low' => '0800200c',
                    'time_mid' => '9a66',
                    'time_hi_and_version' => '11e1',
                    'clock_seq_hi_and_reserved' => '9b',
                    'clock_seq_low' => '21',
                    'node' => 'ff6f8cb0c57d'
                ]
            );
        $this->codec->decode('0800200c-9a66-11e1-9b21-ff6f8cb0c57d');
    }

    public function testBinaryDecoding()
    {
        $this->builderMock->expects($this->exactly(1))
            ->method('build')
            ->with(
                $this->codec,
                [
                    'time_low' => '0800200c',
                    'time_mid' => '9a66',
                    'time_hi_and_version' => '11e1',
                    'clock_seq_hi_and_reserved' => '9b',
                    'clock_seq_low' => '21',
                    'node' => 'ff6f8cb0c57d'
                ]
            );
        $this->codec->decodeBytes(hex2bin('0800200c9a6611e19b21ff6f8cb0c57d'));
    }
}
