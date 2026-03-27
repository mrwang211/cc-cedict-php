<?php

namespace Mrwang211\CcCedictPhp;

class StringUtils
{
    public static function between(string $str, string $from, string $to): ?string
    {
        $fromIdx = mb_strpos($str, $from);
        $toIdx = $from === $to
            ? mb_strpos($str, $to, $fromIdx + 1)  // use offset instead
            : mb_strpos($str, $to);

        if ($fromIdx === false || $toIdx === false || $toIdx - $fromIdx <= 1) {
            return null;
        }

        return mb_substr($str, $fromIdx + 1, $toIdx - $fromIdx - 1);
    }

    public static function betweenFirstAndLast(string $str, string $from, string $to): ?string
    {
        $fromIdx = mb_strpos($str, $from);
        $toIdx = mb_strrpos($str, $to);

        if ($fromIdx === false || $toIdx === false || $toIdx - $fromIdx <= 1) {
            return null;
        }

        return mb_substr($str, $fromIdx + 1, $toIdx - $fromIdx - 1);
    }
}
