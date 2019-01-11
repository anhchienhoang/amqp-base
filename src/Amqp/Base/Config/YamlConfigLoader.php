<?php

namespace Amqp\Base\Config;

use Amqp\Base\Config\Loader\YamlFileLoader;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Resource\FileResource;

class YamlConfigLoader implements Interfaces\Loader
{
    /** @var string */
    private const CACHED_FILE = 'amqpCachedConfig.php';

    /**
     * Configuration file name
     * @var string
     */
    protected $filename;

    /**
     * @var string
     */
    protected $cacheDir;

    /**
     * @param string $filename Configuration file name
     * @param string $cacheDir Directory to store the cached parsed configs
     */
    public function __construct(string $filename, string $cacheDir = null)
    {
        $this->filename = $filename;
        $this->cacheDir = $cacheDir;
    }

    /**
     * {@inheritdoc}
     */
    public function load(): array
    {
        if (file_exists($this->filename) && is_readable($this->filename)) {
            if (null !== $this->cacheDir) {
                if (!is_writable($this->cacheDir)) {
                    throw new Exception(
                        sprintf('Error: the cache dir is not existed or not writable: %s', $this->cacheDir)
                    );
                }

                $configCache = new ConfigCache($this->getCachePath(), true);

                if (!$configCache->isFresh()) {
                    $loader = new YamlFileLoader(new FileLocator());

                    $resource[] = new FileResource($this->filename);

                    $configs = $loader->load($this->filename);

                    $content = (new PhpDumper())->dump($configs);

                    $configCache->write($content, $resource);
                }

                return require $this->getCachePath();
            }

            $loader = new YamlFileLoader(new FileLocator());

            return $loader->load($this->filename);
        }

        throw new Exception(sprintf('Error: Invalid file descriptor %s', $this->filename));
    }

    /**
     * @return string
     */
    protected function getCachePath(): string
    {
        return $this->cacheDir.'/'.static::CACHED_FILE;
    }
}