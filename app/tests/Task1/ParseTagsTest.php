<?php

declare(strict_types=1);

namespace App\Tests\Task1;

use PHPUnit\Framework\TestCase;
use Throwable;

use function App\Task1\parseTags;

/**
 * Test parse tags
 *
 * @covers \App\Task1\parseTags;
 */
class ParseTagsTest extends TestCase
{
    /**
     * Test function Parse Tags
     *
     * @dataProvider dataSuccessCases
     */
    public function testSuccessParseTags(array $expected, string $text): void
    {
        self::assertEqualsCanonicalizing($expected, parseTags($text));
    }

    public function dataSuccessCases(): array
    {
        return [
            [[], 'text text text text'],
            [[], ''],
            [
                [
                    'TAG_NAME' =>
                        ['description' => 'test description', 'data' => 'test data'],
                    'TAG2' =>
                        ['description' => 'test description2', 'data' => 'test data2'],
                ],
                'text text [TAG_NAME:test description]test data[/TAG_NAME] text text
                text text [TAG2:test description2]test data2[/TAG2] text text'
            ],
            [
                ['TAG_NAME' => ['description' => '', 'data' => 'test data']],
                'text text [TAG_NAME]test data  [/TAG_NAME] text text'
            ],
            [
                ['TAG_NAME' => ['description' => '', 'data' => '']],
                'text text [TAG_NAME][/TAG_NAME] text text'],

        ];
    }

    /**
     * Test exception test
     *
     * @dataProvider dataFailCases
     */
    public function testFailParseTags(string $text): void
    {
        self::expectException(Throwable::class);
        parseTags($text);
    }

    public function dataFailCases(): array
    {
        return [
            ['test [][/] text [:][/] text [TAG][/TAG] text
              :test []description]test data[/TAG_NAME] text [/ddd] text
              text text [TAG2:test description2]test data2[/TAG2] text text'],
            ['text text [TAG_NAME]test data  [/TAG_NAME2] text text'],
            ['text text [TAG_NAME][TAG2_NAME][/TAG2_NAME][/TAG_NAME] text text'],
            ['text text [TAG_NAME:desc]test[/TAG_NAME][TAG_NAME]wew[/TAG_NAME]text text'],
        ];
    }
}
