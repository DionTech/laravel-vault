<?php


namespace DionTech\Vault\Tests;


use DionTech\Vault\Support\Contracts\KeyServiceContract;
use Tests\TestCase;

class KeyServiceTest extends TestCase
{
    public function test_get_key()
    {
        $values = [
            'irgendwas',
            'something that might be a little bit longer then running in 32 signs',
            'foobarme',
            'i',
            ''
        ];

        $keyGenerator = $this->app->make(KeyServiceContract::class);

        config(['app.cipher' => 'AES-256-CBC']);

        foreach ($values as $value) {
            $this->assertEquals($keyGenerator->getKey($value), $keyGenerator->getKey($value));
            $this->assertEquals(32, strlen($keyGenerator->getKey($value)));
        }

        config(['app.cipher' => 'AES-128-CBC']);

        foreach ($values as $value) {
            $this->assertEquals($keyGenerator->getKey($value), $keyGenerator->getKey($value));
            $this->assertEquals(16, strlen($keyGenerator->getKey($value)));
        }

        //test the function edge case fill string
        $value = 'i';
        $this->assertEquals(32, strlen($keyGenerator->fillString($value, 32)));

        $this->assertEquals(16, strlen($keyGenerator->fillString($value, 16)));
    }
}
