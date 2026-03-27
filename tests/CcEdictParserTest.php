<?php

namespace Mrwang211\CcCedictPhp\Tests;

use Exception;
use Mrwang211\CcCedictPhp\CcEdictDownloader;
use Mrwang211\CcCedictPhp\CcEdictParser;
use Mrwang211\CcCedictPhp\Definition;
use Mrwang211\PinyinPro\PinyinPro;

class Cache
{
    private const string DICTIONARY_PATH = 'build/cc-edict.txt';

    /**
     * @var array<Definition>
     */
    private static array $dictionary;

    public static function getDictionaryContents(): string
    {
        if (file_exists(self::DICTIONARY_PATH)) {
            return file_get_contents(self::DICTIONARY_PATH);
        } else {
            $downloader = new CcEdictDownloader;
            $contents = $downloader->fetchDictionaryContents();

            if (! $contents) {
                throw new Exception('could not fetch dictionary contents');
            }

            file_put_contents(self::DICTIONARY_PATH, $contents);

            return $contents;
        }
    }

    /**
     * @return Definition[]
     */
    public static function getParsedDictionary(): array
    {
        if (isset(self::$dictionary)) {
            return self::$dictionary;
        }

        $parser = new CcEdictParser(new PinyinPro);
        self::$dictionary = $parser->parseDictionaryContents(self::getDictionaryContents());

        return self::$dictionary;
    }
}

describe('CcEdictParser', function () {
    it('parses phrase correctly', function () {
        $phrase = array_find(Cache::getParsedDictionary(), fn ($word) => $word->getSimplified() === '黑马');

        expect($phrase)->not()->toBeNull()
            ->and($phrase->getTraditional())->toBe('黑馬')
            ->and($phrase->getPinyin())->toBe('hēi mǎ')
            ->and($phrase->getEnglish())->toBe('dark horse/fig. unexpected winner');
    });

    it('parses another phrase correctly', function () {
        $phrase = array_find(Cache::getParsedDictionary(), fn ($word) => $word->getSimplified() === '雾霾');

        expect($phrase)->not()->toBeNull()
            ->and($phrase->getTraditional())->toBe('霧霾')
            ->and($phrase->getPinyin())->toBe('wù mái')
            ->and($phrase->getEnglish())->toBe('haze/smog');
    });

    it('parses idiom correctly', function () {
        $idiom = array_find(Cache::getParsedDictionary(), fn ($word) => $word->getSimplified() === '不到长城非好汉');

        expect($idiom)->not()->toBeNull()
            ->and($idiom->getTraditional())->toBe('不到長城非好漢')
            ->and($idiom->getPinyin())->toBe('bù dào Cháng chéng fēi hǎo hàn')
            ->and($idiom->getEnglish())->toBe('lit. until you reach the Great Wall, you\'re not a proper person; fig. to get over difficulties before reaching the goal');
    });

    it('parses word with nested pinyin in definition correctly', function () {
        $word = array_find(Cache::getParsedDictionary(), fn ($word) => $word->getSimplified() === '你');

        expect($word)->not()->toBeNull()
            ->and($word->getTraditional())->toBe('你')
            ->and($word->getPinyin())->toBe('nǐ')
            ->and($word->getEnglish())->toBe('you (informal, as opposed to courteous 您[nín])');
    });
});
