<?php

declare(strict_types=1);

namespace Napp\Xray\Tests;

use Napp\Xray\Segments\JobSegment;
use PHPUnit\Framework\TestCase;

class JobSegmentTest extends TestCase
{
    public function test_serializes_correctly(): void
    {
        $payload = [
            [
                'doo' =>[
                    'foo' => 'bar'
                ]
            ],
            [
                23 => false
            ]
        ];
        $segment = new JobSegment();
        $segment->setPayload($payload)
            ->setResult(true);

        $serialized = $segment->jsonSerialize();

        $this->assertEquals('success', $serialized['job']['result']);
        $this->assertEquals($payload, $serialized['job']['payload']);
    }

    public function test_serializes_correctly_without_payload(): void
    {
        $segment = new JobSegment();
        $segment->setResult(false);

        $serialized = $segment->jsonSerialize();

        $this->assertEquals('failed', $serialized['job']['result']);
        $this->assertEquals(false, isset($serialized['job']['payload']));
    }
}
