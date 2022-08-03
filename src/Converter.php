<?php

namespace NK\EncodingConverter;

use NK\EncodingConverter\Recursive\Handler;
use NK\EncodingConverter\Encoding\EncodingHandler;
use NK\EncodingConverter\Encoding\HTMLHandler;

class Converter
{
    /**
     * @param array $handlers
     * @param mixed $var
     * @return mixed
     */
    private static function handleVar(array $handlers, $var)
    {
        $recursiveHandler = new Handler($handlers);
        return $recursiveHandler->handleVar($var);
    }

    /**
     * @param mixed $var
     * @return mixed
     */
    public static function fromUtf8ToCp1252($var)
    {
        $handlers = [
            (new EncodingHandler())
                ->from(EncodingHandler::UTF8)
                ->to(EncodingHandler::CP1252)
        ];
        return self::handleVar($handlers, $var);
    }

    /**
     * @param mixed $var
     * @return mixed
     */
    public static function fromCp1252ToUtf8($var)
    {
        $handlers = [
            (new EncodingHandler())
                ->from(EncodingHandler::CP1252)
                ->to(EncodingHandler::UTF8)
        ];
        return self::handleVar($handlers, $var);
    }

    /**
     * @param mixed $var
     * @param array $emojiFieldsList
     * @return mixed
     */
    public static function fromUtf8ToCp1252EncodingEmojis($var, array $emojiFieldsList = [])
    {
        $handlers = [
            (new EncodingHandler())
                ->from(EncodingHandler::UTF8)
                ->to(EncodingHandler::CP1252)
                ->setEmojiFieldsList($emojiFieldsList)
        ];
        return self::handleVar($handlers, $var);
    }

    /**
     * @param mixed $var
     * @param array $emojiFieldsList
     * @return mixed
     */
    public static function fromCp1252ToUtf8DecodingEmojis($var, array $emojiFieldsList = [])
    {
        $handlers = [
            (new EncodingHandler())
                ->from(EncodingHandler::CP1252)
                ->to(EncodingHandler::UTF8),
            (new HTMLHandler())
                ->setEncoding(EncodingHandler::UTF8)
                ->setEmojiFieldsList($emojiFieldsList)
        ];
        return self::handleVar($handlers, $var);
    }

    /**
     * @param mixed $var
     * @param array $skippedFieldsList
     * @return mixed
     */
    public static function fromCp1252ToUtf8DecodingHtmlEntity($var, array $skippedFieldsList = [])
    {
        $handlers = [
            (new EncodingHandler())
                ->from(EncodingHandler::CP1252)
                ->to(EncodingHandler::UTF8),
            (new HTMLHandler())
                ->setEncoding(EncodingHandler::UTF8)
                ->setSkippedFieldsList($skippedFieldsList)
        ];
        return self::handleVar($handlers, $var);
    }
}
