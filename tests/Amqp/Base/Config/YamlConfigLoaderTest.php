<?php

namespace Test\Amqp\Base\Config;

use Amqp\Base\Config\YamlConfigLoader;
use PHPUnit\Framework\TestCase;

class YamlConfigLoaderTest extends TestCase
{
    /**
     * @var string
     */
    protected $cachedFile = '/tmp/amqpCachedConfig.php';

    /**
     * @covers YamlConfigLoader::load
     */
    public function testLoadCachedConfigs(): void
    {
        $loader = new YamlConfigLoader(__DIR__.'/Fixture/test.yml', '/tmp');

        $configs = $loader->load();

        $expected = array(
            'foo_string' => 'foo_string',
            'foo_arr' => [1, 2, ['name' => 'test']],
            'test' => 1,
        );

        $this->assertEquals($expected, $configs);

        $this->assertFileExists($this->cachedFile);
    }

    /**
     * @covers YamlConfigLoader::load
     */
    public function testLoadConfigsWithoutCache(): void
    {
        if (file_exists($this->cachedFile)) {
            unlink($this->cachedFile);
        }

        $loader = new YamlConfigLoader(__DIR__.'/Fixture/test.yml');

        $configs = $loader->load();

        $expected = array(
            'foo_string' => 'foo_string',
            'foo_arr' => [1, 2, ['name' => 'test']],
            'test' => 1,
        );

        $this->assertEquals($expected, $configs);

        $this->assertFileNotExists($this->cachedFile);
    }
}