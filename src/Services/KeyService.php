<?php


namespace DionTech\Vault\Services;


use DionTech\Vault\Support\Contracts\KeyServiceContract;
use Illuminate\Support\Str;

class KeyService implements KeyServiceContract
{
    public function getKey(string $value): string
    {
        $hashed = md5($value);

        $strLen = $this->getStrLen();

        if (strlen($hashed) === $strLen) {
            return $hashed;
        }

        if (strlen($hashed) > $strLen) {
            return Str::limit($hashed, $strLen, '');
        }

        return $this->fillString($hashed, $strLen);
    }

    public function fillString(string $value, int $strLen): string
    {
        while (strlen($value) < $strLen) {
            $value .=  substr($value, -1);
        }

        return $value;
    }

    protected function getStrLen(): int
    {
        switch (config('app.cipher')) {
            case 'AES-256-CBC': return 32;
            case 'AES-128-CBC': return 16;
            default: return 16;
        }
    }
}
