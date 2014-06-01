<?php

namespace IKTO\PgiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ikto_pgi');
        $rootNode
            ->children()
            ->append($this->getConnectionsNode())
        ;

        return $treeBuilder;
    }

    protected function getConnectionsNode()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('connections');

        $connectionsNode = $rootNode
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('name')
            ->prototype('array')
        ;

        $connectionsNode
            ->children()
                ->scalarNode('dbname')->isRequired()->end()
                ->scalarNode('host')->defaultValue('localhost')->end()
                ->scalarNode('port')->defaultValue(5432)->end()
                ->scalarNode('user')->defaultNull()->end()
                ->scalarNode('password')->defaultNull()->end()
            ->end()
        ;

        return $rootNode;
    }
}
