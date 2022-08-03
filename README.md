# encoding-converter
CP1252 and UTF-8 encodings converter with html entities and emojis support

### Examples

json_* functions for instance work with UTF-8 encoding:
```
    /**
     * @throws JsonException
     */
    public function Show(): void
    {
        echo json_encode(Converter::fromCp1252ToUtf8DecodingHtmlEntity($this->response), JSON_THROW_ON_ERROR);
    }
```
The same for csv files, the content of which must be presented in UTF-8 encoding

```
$rows = Converter::fromCp1252ToUtf8($rows);
```
