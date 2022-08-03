<?php

namespace NK\EncodingConverter\Encoding;

class EncodingHandler extends AbstractHandler
{
    /** @var string */
    private $from;
    /** @var string */
    private $to;
    /** @var string[] */
    private $emojiFieldsList;
    /** @var bool|int|string */
    private $originalSubstitute;

    public const UTF8 = 'UTF-8';
    public const CP1252 = 'CP1252';

    public function __construct()
    {
        $this->originalSubstitute = mb_substitute_character();
    }

    public function from(string $encoding): self
    {
        $this->from = $encoding;
        return $this;
    }

    public function to(string $encoding): self
    {
        $this->to = $encoding;
        return $this;
    }

    public function setEmojiFieldsList(array $list): self
    {
        $this->emojiFieldsList = $list;
        return $this;
    }

    /**
     * @param null|bool|float|int|string $value
     * @param string|int $key
     * @return null|bool|float|int|string
     */
    public function handleValue($value, $key = '')
    {
        if ($this->skipValue($value)) {
            return $value;
        }

        if (isset($this->emojiFieldsList)) {
            mb_substitute_character(
                empty($this->emojiFieldsList) || in_array($key, $this->emojiFieldsList, TRUE)
                    ? 'entity'
                    : $this->originalSubstitute
            );
        }
        $convertedValue = mb_convert_encoding($value, $this->to, $this->from);

        mb_substitute_character($this->originalSubstitute);
        return $convertedValue;
    }
}
