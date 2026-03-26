<?php

namespace Mrwang211\CcCedictPhp;

class Word
{
    private string $simplified;

    private string $traditional;

    private string $pinyin;

    private string $english;

    public function __construct(string $simplified, string $traditional, string $pinyin, string $english)
    {
        $this->simplified = $simplified;
        $this->traditional = $traditional;
        $this->pinyin = $pinyin;
        $this->english = $english;
    }

    public function getSimplified(): string
    {
        return $this->simplified;
    }

    public function setSimplified(string $simplified): void
    {
        $this->simplified = $simplified;
    }

    public function getTraditional(): string
    {
        return $this->traditional;
    }

    public function setTraditional(string $traditional): void
    {
        $this->traditional = $traditional;
    }

    public function getPinyin(): string
    {
        return $this->pinyin;
    }

    public function setPinyin(string $pinyin): void
    {
        $this->pinyin = $pinyin;
    }

    public function getEnglish(): string
    {
        return $this->english;
    }

    public function setEnglish(string $english): void
    {
        $this->english = $english;
    }

    public function __toString(): string
    {
        return "<simplified: $this->simplified, traditional: $this->traditional, pinyin: $this->pinyin, english: $this->english>";
    }
}
