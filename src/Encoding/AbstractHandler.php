<?php

namespace NK\EncodingConverter\Encoding;

use NK\EncodingConverter\Recursive\RecursiveContract;

abstract class AbstractHandler implements RecursiveContract
{
    /**
     * Pass only the alphabetic string
     *
     * @param mixed $value
     * @return bool
     */
    protected function skipValue($value): bool
    {
        return empty($value) || is_numeric($value) || !is_string($value);
    }
}
