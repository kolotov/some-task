<?php

declare(strict_types=1);

namespace App\Tests\Task1;

use PHPUnit\Framework\TestCase;

use function App\Task1\parseTags;

/**
 * Test parse tags
 *
 * @covers \App\Task1\ParseTags;
 */
class ParseTagsTest extends TestCase
{
    /**
     * Test function Parse Tags
     *
     * @dataProvider dataCases
     */
    public function testParseTags(array $expected, string $text): void
    {
        self::assertEqualsCanonicalizing($expected, parseTags($text));
    }

    public function dataCases(): array
    {
        return [
            [
                'TAG_NAME' => ['description' => 'test description', 'data' => 'test data'],
                '[TAG_NAME:test description]test data[/TAG_NAME]'
            ],
            [[], ''],
            [[], '[TAG_NAME:description]data[TAG_NAME]'],
            [[], 'TAG_NAME:description]data[/TAG_NAME]'],
            [
                [
                    'TAG_NAME1' => ['description' => 'description1', 'data' => 'data1'],
                    'TAG_NAME2' => ['description' => 'description2', 'data' => 'data2'],
                ],
                '[TAG_NAME1:description1]data1[/TAG_NAME1] [TAG_NAME2:description2]data2[/TAG_NAME2]'
            ],
        ];
    }
}
