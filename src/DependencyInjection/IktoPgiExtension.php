<?php

namespace IKTO\PgiBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class IktoPgiExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $dataCollector = $container->getDefinition('pgi.profiler.data_collector');

        $connections = $config['connections'];
        foreach ($connections as $connectionName => $connectionParams) {
            $loggerId = 'pgi.logger.' . $connectionName;
            $logger = new ChildDefinition('pgi.logger.abstract');
            $container->setDefinition($loggerId, $logger);
            $loggerRef = new Reference($loggerId);

            $definition = new ChildDefinition('pgi.db.abstract');
            $definition->setArguments(array(
                "host={$connectionParams['host']} port={$connectionParams['port']} dbname={$connectionParams['dbname']}",
                $connectionParams['user'],
                $connectionParams['password'],
            ));

            $definition->addMethodCall('setLogger', array($loggerRef));
            $container->setDefinition('pgi.db.' . $connectionName, $definition);

            $dataCollector->addMethodCall('setLogger', array($connectionName, $loggerRef));
        }
    }
}
