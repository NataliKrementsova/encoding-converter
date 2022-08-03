<?php

namespace NK\EncodingConverter\Recursive;

class Handler
{
    private const RECURSION_MARKER = '___we___been___here___';

    /** @var RecursiveContract[] */
    private $handlers;

    /**
     * @param RecursiveContract[] $handlers
     */
    public function __construct(array $handlers)
    {
        $this->handlers = $handlers;
    }

    /**
     * @param mixed $var
     * @return mixed
     */
    public function handleVar($var)
    {
        if (empty($var)) {
            return $var;
        }

        try {
            if (is_array($var)) {
                return $this->handleArray($var);
            }

            if (is_object($var)) {
                return $this->handleObject($var);
            }
        } catch (RecursiveException $e) {
            return $var;
        }

        return $this->handlePrimitive($var);
    }

    /**
     * @param array $values
     * @return array
     * @throws RecursiveException
     */
    private function handleArray(array $values): array
    {
        if (isset($values[self::RECURSION_MARKER])) {
            throw new RecursiveException();
        }
        $values[self::RECURSION_MARKER] = TRUE;
        foreach ($values as $key => $value) {
            $values[$key] = $this->isIterable($value)
                ? $this->handleVar($value)
                : $this->handlePrimitive($value, $key);
        }
        unset($values[self::RECURSION_MARKER]);
        return $values;
    }

    /**
     * @param object $object
     * @return object
     * @throws RecursiveException
     */
    private function handleObject(object $object): object
    {
        if (isset($object->{self::RECURSION_MARKER})) {
            throw new RecursiveException();
        }
        $object->{self::RECURSION_MARKER} = TRUE;
        $reflection = new \ReflectionObject($object);
        $properties = $reflection->getProperties();
        foreach ($properties as $property) {
            $property->setAccessible(TRUE);
            $value = $property->getValue($object);
            $property->setValue($object, $this->isIterable($value)
                ? $this->handleVar($value)
                : $this->handlePrimitive($value, $property->name)
            );
        }
        unset($object->{self::RECURSION_MARKER});
        return $object;
    }

    /**
     * @param null|bool|float|int|string $value
     * @param string|int $key
     * @return null|bool|float|int|string
     */
    private function handlePrimitive($value, $key = '')
    {
        foreach ($this->handlers as $handler) {
            $value = $handler->handleValue($value, $key);
        }
        return $value;
    }

    /**
     * @param mixed $var
     * @return bool
     */
    private function isIterable($var): bool
    {
        return is_array($var) || is_object($var);
    }
}
