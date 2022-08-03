<?php

namespace NK\EncodingConverter\Tests\Recursive;

use NK\EncodingConverter\Recursive\Handler;
use NK\EncodingConverter\Recursive\RecursiveContract;
use PHPUnit\Framework\TestCase;

class HandlerTest extends TestCase
{
    public function testHandlerVarEmptyValue(): void
    {
        $handler = $this->createMock(RecursiveContract::class);
        $recursiveHandler = new Handler([$handler]);
        self::assertNull($recursiveHandler->handleVar(NULL));
    }

    /** TODO: failure test:: recursive array looping because of pass by value to a method */
    public function testHandlerVarArray(): void
    {
        self::markTestSkipped('Failure on recursive array');
        $handler = $this->createMock(RecursiveContract::class);
        $handler->method('handleValue')->willReturn(1);
        $recursiveHandler = new Handler([$handler]);
        $recursiveArray = ['1', 2, 'string', ['0', 0]];
        $recursiveArray[] = &$recursiveArray;
        $expected = [1, 1, 1, [1, 1]];
        $expected[] = &$expected;
        self::assertEquals($expected, $recursiveHandler->handleVar($recursiveArray));
    }

    public function testHandlerVarObject(): void
    {
        $handler = $this->createMock(RecursiveContract::class);
        $handler->method('handleValue')->willReturn(1);
        $recursiveHandler = new Handler([$handler]);
        $object = new \stdClass();
        $object->prop1 = [2, $object];
        $fakeObj = new \stdClass();
        $fakeObj->prop = ['3'];
        $fakeObj->existedObj = $object;
        $object->prop2 = $fakeObj;

        $expectedObj = clone $object;
        $expectedObj->prop1 = [1, $expectedObj];

        $expectedFakeObj = clone $fakeObj;
        $expectedFakeObj->prop = [1];
        $expectedFakeObj->existedObj = $expectedObj;

        $expectedObj->prop2 = $expectedFakeObj;

        self::assertEquals($expectedObj, $recursiveHandler->handleVar($object));
    }
}
