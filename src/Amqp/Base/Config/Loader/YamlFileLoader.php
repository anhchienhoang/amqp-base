<?php

namespace Amqp\Base\Config\Loader;

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

class YamlFileLoader extends FileLoader
{
    /**
     * Loads a resource.
     *
     * @param mixed       $resource The resource
     * @param string|null $type The resource type or null if unknown
     *
     * @return array
     * @throws \Exception If something went wrong
     */
    public function load($resource, $type = null): array
    {
        $config = $this->parseYaml($resource);
        $parsedConfigs = [$config];
        $resourceDir = \dirname($resource);

        if (isset($config['imports']) && \is_array($config['imports'])) {
            foreach ($config['imports'] as $import) {
                $parsedConfigs[] = (array)$this->import($resourceDir.'/'.$import['resource']);
            }
        }

        $configs = array_merge_recursive(...$parsedConfigs);
        unset($configs['imports']);

        return $configs;
    }

    /**
     * Parse yaml content|file
     *
     * @param string|mixed $resource
     *
     * @return array
     */
    protected function parseYaml($resource): array
    {
        return Yaml::parse($this->loadResourceData($resource));
    }

    /**
     * Load resource data
     *
     * @param string $resource Resource filename
     *
     * @return string
     */
    protected function loadResourceData($resource): string
    {
        return file_get_contents($resource);
    }

    /**
     * Returns whether this class supports the given resource.
     *
     * @param mixed       $resource A resource
     * @param string|null $type The resource type or null if unknown
     *
     * @return bool True if this class supports the given resource, false otherwise
     */
    public function supports($resource, $type = null): bool
    {
        return preg_match('/\.ya?ml$/i', $resource);
    }
}