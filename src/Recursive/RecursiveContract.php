<?php

namespace NK\EncodingConverter\Recursive;

interface RecursiveContract
{
    /**
     * @param null|bool|float|int|string $value
     * @param string|int $key
     * @return null|bool|float|int|string
     */
    public function handleValue($value, $key = '');
}
