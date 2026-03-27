<?php

namespace Mrwang211\CcCedictPhp;

use Mrwang211\PinyinPro\PinyinPro;

class CcEdictParser
{
    private const string COMMENT_PREFIX = '#';

    private const string OPEN_BRACKET = '[';

    private const string CLOSE_BRACKET = ']';

    private PinyinPro $pinyinPro;

    public function __construct(PinyinPro $pinyinPro)
    {
        $this->pinyinPro = $pinyinPro;
    }

    /**
     * @return array<Definition>
     */
    public function parseDictionaryContents(string $contents): array
    {
        $lines = explode("\n", $contents);

        return array_filter(array_map(fn ($l) => $this->parseLine($l), $lines));
    }

    public function convertAllPinyinTonesInBracketsInPlace(string $str): string
    {
        return preg_replace_callback(
            '/\[([^]]+)]/u',
            fn (array $matches) => self::OPEN_BRACKET.$this->pinyinPro->convert($matches[1]).self::CLOSE_BRACKET,
            $str
        );
    }

    // TODO: rewrite this
    private function parseLine(string $line): ?Definition
    {
        if (str_starts_with($line, self::COMMENT_PREFIX)) {
            return null;
        }

        $idxOfOpenBracket = mb_strpos($line, self::OPEN_BRACKET);

        $english = StringUtils::betweenFirstAndLast($line, '/', '/');
        $english = $this->convertAllPinyinTonesInBracketsInPlace($english);

        [$traditional, $simplified] = explode(' ', mb_trim(mb_substr($line, 0, $idxOfOpenBracket - 1)));

        $rawPinyin = StringUtils::between($line, self::OPEN_BRACKET, self::CLOSE_BRACKET);
        $pinyinWithTones = $this->pinyinPro->convert($rawPinyin);

        return new Definition($simplified, $traditional, $pinyinWithTones, $english);
    }
}
