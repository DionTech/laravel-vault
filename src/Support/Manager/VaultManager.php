<?php


namespace DionTech\Vault\Support\Manager;


use DionTech\Vault\Models\Vault;
use DionTech\Vault\Support\Contracts\VaultServiceContract;
use Illuminate\Database\Eloquent\Model;

/**
 * Class VaultManager
 * @package DionTech\Vault\Support\Manager
 */
class VaultManager
{
    /**
     * @var string|mixed
     */
    protected string $key;

    /**
     * @var
     */
    protected $vault;

    /**
     * @var
     */
    protected $context;

    /**
     * VaultManager constructor.
     */
    public function __construct()
    {
        $this->key = env("APP_KEY");
    }

    /**
     * @param string $key
     * @return $this
     */
    public function useKey(string $key): VaultManager
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @param Vault|string $vault
     * @return $this
     */
    public function open($vault): VaultManager
    {
        if (is_string($vault)) {
            return $this->createVault($vault);
        }

        $this->vault = $vault;

        return $this;
    }

    /**
     * @param string $secret
     * @return string
     */
    public function get(string $secret): string
    {
        return app()->makeWith(VaultServiceContract::class, [
            'key' => $this->key
        ])->getSecret($this->vault, $secret);
    }

    /**
     * @param string $secret
     * @param string $value
     */
    public function add(string $secret, string $value): void
    {
        app()->makeWith(VaultServiceContract::class, [
            'key' => $this->key
        ])->addSecret($this->vault, $secret, $value);
    }

    /**
     * @param string $secret
     * @param string $value
     */
    public function overwrite(string $secret, string $value): void
    {
        app()->makeWith(VaultServiceContract::class, [
            'key' => $this->key
        ])->overwriteSecret($this->vault, $secret, $value);
    }

    /**
     * @param mixed $context
     * @return VaultManager
     */
    public function setContext($context)
    {
        $this->context = $context;
        return $this;
    }

    protected function createVault(string $vault): VaultManager
    {
        $params = ['name' => $vault];

        if ($this->context instanceof Model) {
            $params['model_id'] = $this->context->model_id;
            $params['model_type'] = get_class($this->context);
        }

        $this->vault = Vault::create($params);

        return $this;
    }
}
