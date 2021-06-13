<?php

namespace Orchestra\Testbench\Foundation;

use Orchestra\Testbench\Concerns\CreatesApplication;

class Application
{
    use CreatesApplication {
        resolveApplication as protected resolveApplicationFromTrait;
    }

    /**
     * The application base path.
     *
     * @var string
     */
    protected $basePath;

    /**
     * The application resolving callback.
     *
     * @var callable|nulls
     */
    protected $resolvingCallback;

    /**
     * Create new application resolver.
     *
     * @param  string  $basePath
     * @param  callable|null  $resolvingCallback
     */
    public function __construct(string $basePath, ?callable $resolvingCallback = null)
    {
        $this->basePath = $basePath;
        $this->resolvingCallback = $resolvingCallback;
    }

    /**
     * Create new application instance.
     *
     * @param  string  $basePath
     * @param  callable|null  $resolvingCallback
     *
     * @return \Illuminate\Foundation\Application
     */
    public static function create(string $basePath, ?callable $resolvingCallback = null)
    {
        return (new static($basePath, $resolvingCallback))->createApplication();
    }

    /**
     * Resolve application implementation.
     *
     * @return \Illuminate\Foundation\Application
     */
    protected function resolveApplication()
    {
        return \tap($this->resolveApplicationFromTrait(), function ($app) {
            if (\is_callable($this->resolvingCallback)) {
                \call_user_func($this->resolvingCallback, $app);
            }
        });
    }

    /**
     * Get base path.
     *
     * @return string
     */
    protected function getBasePath()
    {
        return $this->basePath;
    }
}
