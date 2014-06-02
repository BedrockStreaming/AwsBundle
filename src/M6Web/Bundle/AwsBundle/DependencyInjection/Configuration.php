<?php

namespace M6Web\Bundle\AwsBundle\DependencyInjection;

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
        $rootNode    = $treeBuilder->root('m6web_aws');

        $rootNode
            ->children()
                ->scalarNode('aws_factory_class')->end()
                ->arrayNode('credentials')
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('key')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('secret')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('region')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('scheme')->end()
                            ->scalarNode('base_url')->end()
                            ->scalarNode('signature')->end()
                            ->scalarNode('signature_service')->end()
                            ->scalarNode('signature_region')->end()
                            ->arrayNode('curl_options')
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('request_options')
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('command_params')
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('clients')
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('credential')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('service')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('region')
                            ->end()
                            ->scalarNode('base_url')
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('dynamodb')
                    ->requiresAtLeastOneElement()
                    ->prototype('array')
                        ->children()
                            ->scalarNode('client')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->arrayNode('cache')
                                ->children()
                                    ->scalarNode('key_prefix')->defaultValue(null)->end()
                                    ->scalarNode('ttl')->defaultValue(86400)->end()
                                    ->scalarNode('service')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('sqs')
                    ->requiresAtLeastOneElement()
                    ->prototype('array')
                        ->children()
                            ->scalarNode('client')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('s3')
                    ->children()
                    ->arrayNode('buckets')
                        ->requiresAtLeastOneElement()
                        ->useAttributeAsKey('alias')
                        ->prototype('array')
                            ->children()
                                ->scalarNode('client')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode('name')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }

}