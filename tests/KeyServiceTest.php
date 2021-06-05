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
            'foobarme'
        ];

        $keyGenerator = $this->app->make(KeyServiceContract::class);

        config(['app.cipher' => 'AES-256-CBC']);

        foreach ($values as $value) {
            $this->assertEquals($keyGenerator->getKey($value), $keyGenerator->getKey($value));
            $this->assertEquals(32, $keyGenerator->getKey($value));
        }


        config(['app.cipher' => 'AES-128-CBC']);

        foreach ($values as $value) {
            $this->assertEquals($keyGenerator->getKey($value), $keyGenerator->getKey($value));
            $this->assertEquals(16, $keyGenerator->getKey($value));
        }
    }
}
