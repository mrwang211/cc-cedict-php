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
     * @return array<Word>
     */
    public function parseDictionaryContents(string $contents): array
    {
        $lines = explode("\n", $contents);

        return array_filter(array_map(fn ($l) => $this->parseLine($l), $lines));
    }

    public function convertAllPinyinTonesInBracketsInPlace(string $str): string
    {
        $result = StringUtils::between($str, self::OPEN_BRACKET, self::CLOSE_BRACKET);

        if ($result === null) {
            return $str;
        }

        $answer = '';

        while ($result != null) {
            [$pinyinToConvert, $idxOfOpenBracket, $idxOfCloseBracket] = $result;

            $answer .= mb_substr($str, 0, $idxOfOpenBracket + 1).$this->pinyinPro->convert($pinyinToConvert).self::CLOSE_BRACKET;
            $str = mb_substr($str, $idxOfCloseBracket + 1);
            $result = StringUtils::between($str, self::OPEN_BRACKET, self::CLOSE_BRACKET);
        }

        return $answer.$str;
    }

    // TODO: rewrite this
    private function parseLine(string $line): ?Word
    {
        if (str_starts_with($line, self::COMMENT_PREFIX)) {
            return null;
        }

        $idxOfOpenBracket = mb_strpos($line, self::OPEN_BRACKET);

        [$betweenSlashes] = StringUtils::betweenFirst($line, '/', '/');
        $english = $this->convertAllPinyinTonesInBracketsInPlace($betweenSlashes);

        [$traditional, $simplified] = explode(' ', mb_trim(mb_substr($line, 0, $idxOfOpenBracket - 1)));

        [$rawPinyin] = StringUtils::between($line, self::OPEN_BRACKET, self::CLOSE_BRACKET);
        $pinyinWithTones = $this->pinyinPro->convert($rawPinyin);

        return new Word($simplified, $traditional, $pinyinWithTones, $english);
    }
}
