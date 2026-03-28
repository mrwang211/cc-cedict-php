<?php

namespace Mrwang211\CcCedictPhp;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class CcCedictDownloader
{
    private const string DEFAULT_DOWNLOAD_URL = 'https://www.mdbg.net/chinese/export/cedict/cedict_1_0_ts_utf-8_mdbg.txt.gz';

    private string $url;

    public function __construct(string $url = self::DEFAULT_DOWNLOAD_URL)
    {
        $this->url = $url;
    }

    public function fetchDictionaryContents(): ?string
    {
        try {
            $response = Http::get($this->url);

            $decoded = gzdecode($response->body());
            if ($decoded) {
                return $decoded;
            }
        } catch (ConnectionException $e) {
        }

        return null;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }
}
