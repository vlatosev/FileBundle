<?php

namespace EDV\FileBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ed_file_settings');
      /** @var $rootNode ArrayNodeDefinition */
      $rootNode
            ->children()
              ->scalarNode('root_image_webdir')
                ->cannotBeEmpty()
                ->defaultValue('image-uploads')
              ->end()
              ->arrayNode('image_types')
                ->prototype('array')
                  ->children()
                    ->scalarNode('transform')->isRequired()->end()
                    ->scalarNode('width')->defaultValue(null)->end()
                    ->scalarNode('height')->defaultValue(null)->end()
                    ->scalarNode('default_image')->defaultValue(null)->end()
                  ->end()
              ->end()
            ->end();

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
