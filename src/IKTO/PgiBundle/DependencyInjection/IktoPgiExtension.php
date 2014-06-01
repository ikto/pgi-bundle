<?php

namespace IKTO\PgiBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class IktoPgiExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $connections = $config['connections'];
        foreach ($connections as $connectionName => $connectionParams) {
            $definition = new Definition(
                'IKTO\\PgI\\Database',
                array(
                    "host={$connectionParams['host']} port={$connectionParams['port']}",
                    $connectionParams['user'],
                    $connectionParams['password'],
                )
            );
            $container->setDefinition('pgi.' . $connectionName, $definition);
        }
    }
}
