<?php

namespace NK\EncodingConverter\Encoding;

class HTMLHandler extends AbstractHandler
{
    /** @var string[] */
    private $emojiFieldsList;
    /** @var string[] */
    private $skippedFieldsList;
    /** @var string */
    private $encoding;
    /** @var int */
    private $flags;

    public function __construct()
    {
        $this->emojiFieldsList = [];
        $this->skippedFieldsList = [];
        $this->flags = ENT_QUOTES | ENT_HTML5;
    }

    public function setEncoding(string $encoding): self
    {
        $this->encoding = $encoding;
        return $this;
    }

    public function setEmojiFieldsList(array $list): self
    {
        $this->emojiFieldsList = $list;
        return $this;
    }

    public function setSkippedFieldsList(array $list): self
    {
        $this->skippedFieldsList = $list;
        return $this;
    }

    public function setFlags(int $flags): self
    {
        $this->flags = $flags;
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

        if (
            $this->emptyLists()
            || (!empty($this->emojiFieldsList) && in_array($key, $this->emojiFieldsList, TRUE))
            || (!empty($this->skippedFieldsList) && !in_array($key, $this->skippedFieldsList, TRUE))
        ) {
            return html_entity_decode($value, $this->flags, $this->encoding);
        }

        return $value;
    }

    private function emptyLists(): bool
    {
        return empty($this->emojiFieldsList) && empty($this->skippedFieldsList);
    }
}
