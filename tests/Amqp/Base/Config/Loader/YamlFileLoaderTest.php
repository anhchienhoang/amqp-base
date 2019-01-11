<?php

namespace Test\Amqp\Base\Config\Loader;

use Amqp\Base\Config\Loader\YamlFileLoader;
use PHPUnit\Framework\TestCase;

class YamlFileLoaderTest extends TestCase
{
    /**
     * Test configuration load with imports
     */
    public function testLoadWithImports()
    {

        $mockLoader = $this->getMockBuilder(YamlFileLoader::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'loadResourceData'
            ])->getMock();

        $mockLoader->method('loadResourceData')->willReturnCallback(function ($filename) {
            if ('/cache/test.yml' === $filename) {
                return <<<YAML
imports:
    - { resource: "foo.yml" }
test:
    1
YAML;
            }

            if ('/cache/foo.yml' === $filename) {
                return <<<YAML
foo_string: "foo_string"
foo_arr:
    - 1
    - 2
    - { name: "test" }
YAML;
            }
        });

        $expected = array(
            'foo_string' => 'foo_string',
            'foo_arr' => [1, 2, ['name' => 'test']],
            'test' => 1,
        );

        $config = $mockLoader->load('/cache/test.yml');
        $this->assertEquals($config, $expected);
    }
}