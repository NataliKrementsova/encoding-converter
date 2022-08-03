<?php

namespace NK\EncodingConverter\Tests;

use NK\EncodingConverter\Converter;
use PHPUnit\Framework\TestCase;

class ConverterTest extends TestCase
{
    public function testFromUtf8ToCp1252(): void
    {
        $expectedResultFile = file_get_contents(__DIR__ . '/assets/UTF8toCP1252.txt');
        $inputFile = file_get_contents(__DIR__ . '/assets/UTF8.txt');
        $resultFile = Converter::fromUtf8ToCp1252($inputFile);
        self::assertEquals($expectedResultFile, $resultFile);

    }

    public function testFromCp1252ToUtf8(): void
    {
        $expectedResultFile = file_get_contents(__DIR__ . '/assets/CP1252toUTF8.txt');
        $inputFile = file_get_contents(__DIR__ . '/assets/CP1252.txt');
        $resultFile = Converter::fromCp1252ToUtf8($inputFile);
        self::assertEquals($expectedResultFile, $resultFile);
    }

    public function testFromUtf8ToCp1252EncodingEmojis(): void
    {
        $expectedResultFile = file_get_contents(__DIR__ . '/assets/CP1252EncodedEmojis.txt');
        $inputFile = file_get_contents(__DIR__ . '/assets/UTF8.txt');
        $resultFile = Converter::fromUtf8ToCp1252EncodingEmojis($inputFile);
        self::assertEquals($expectedResultFile, $resultFile);
    }

    public function testFromUtf8ToCp1252EncodingEmojisFilledEmojisArray(): void
    {
        $emojiFields = ['EncodedEmojis'];

        $inputFile = file_get_contents(__DIR__ . '/assets/UTF8.txt');
        $testedArray[$emojiFields[0]] = $testedArray['SkippedForEmojis'] = $inputFile;

        $testedClass = new \stdClass();
        $testedClass->{$emojiFields[0]} = $testedClass->SkippedForEmojis = $inputFile;
        $testedClass->EncodedArray = $testedArray;

        $expectedResultArray[$emojiFields[0]] = file_get_contents(__DIR__ . '/assets/CP1252EncodedEmojis.txt');
        $expectedResultArray['SkippedForEmojis'] = file_get_contents(__DIR__ . '/assets/UTF8toCP1252.txt');

        $expectedResultClass = new \stdClass();
        $expectedResultClass->{$emojiFields[0]} = file_get_contents(__DIR__ . '/assets/CP1252EncodedEmojis.txt');
        $expectedResultClass->SkippedForEmojis = file_get_contents(__DIR__ . '/assets/UTF8toCP1252.txt');
        $expectedResultClass->EncodedArray = $expectedResultArray;

        $result = Converter::fromUtf8ToCp1252EncodingEmojis($testedClass, $emojiFields);
        self::assertEquals($expectedResultClass, $result);
    }

    public function testFromCp1252ToUtf8DecodingEmojis(): void
    {
        $expectedResultFile = file_get_contents(__DIR__ . '/assets/UTF8.txt');
        $inputFile = file_get_contents(__DIR__ . '/assets/CP1252EncodedEmojis.txt');
        $resultFile = Converter::fromCp1252ToUtf8DecodingEmojis($inputFile);
        self::assertEquals($expectedResultFile, $resultFile);
    }

    public function testFromCp1252ToUtf8DecodingHtmlEntity(): void
    {
        $skippedForEntityDecoding = ['SkippedForDecoding'];

        $inputFile = file_get_contents(__DIR__ . '/assets/CP1252EncodedEmojis.txt');
        $testedArray['Decoded'] = $testedArray[$skippedForEntityDecoding[0]] = $inputFile;

        $testedClass = new \stdClass();
        $testedClass->Decoded = $testedClass->{$skippedForEntityDecoding[0]} = $inputFile;
        $testedClass->DecodedArray = $testedArray;

        $expectedResultArray['Decoded'] = file_get_contents(__DIR__ . '/assets/UTF8.txt');
        $expectedResultArray[$skippedForEntityDecoding[0]] = file_get_contents(
            __DIR__ . '/assets/UTF8WithHtmlEntities.txt'
        );

        $expectedResultClass = new \stdClass();
        $expectedResultClass->Decoded = file_get_contents(__DIR__ . '/assets/UTF8.txt');
        $expectedResultClass->{$skippedForEntityDecoding[0]} = file_get_contents(
            __DIR__ . '/assets/UTF8WithHtmlEntities.txt'
        );
        $expectedResultClass->DecodedArray = $expectedResultArray;

        $result = Converter::fromCp1252ToUtf8DecodingHtmlEntity($testedClass, $skippedForEntityDecoding);
        self::assertEquals($expectedResultClass, $result);
    }
}
