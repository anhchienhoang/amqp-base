<?php

namespace Amqp\Base\Config;

class PhpDumper
{
    /**
     * @param array $configs
     *
     * @return string
     */
    public function dump(array $configs): string
    {
        $stringConfigs = var_export($configs, true);
        return <<<EOF
<?php

return {$stringConfigs};
EOF;
    }
}