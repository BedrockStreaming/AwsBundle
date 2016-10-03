<?php

namespace M6Web\Bundle\AwsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class M6WebAwsExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        if (!$config['disable_data_collector']) {
            $loader->load('data_collector.yml');
        }

        if (!empty($config['aws_factory_class'])) {
            $container->setParameter('m6web_aws.aws_factory.class', $config['aws_factory_class']);
        }

        $credentials = $config['credentials'];
        $clients     = $config['clients'];

        foreach ($clients as $name => $client) {
            $this->loadClient($container, $name, $client, $credentials);
        }

        if (array_key_exists('s3', $config)) {
            $this->loadS3($container, $config['s3']);
        }

        if (array_key_exists('sqs', $config)) {
            $this->loadProxyClient($container, $config['sqs'], 'sqs');
        }

        if (array_key_exists('sts', $config)) {
            $this->loadProxyClient($container, $config['sts'], 'sts');
        }

        if (array_key_exists('dynamodb', $config)) {
            $this->loadProxyClient(
                $container,
                $config['dynamodb'],
                'dynamodb',
                function ($clientDefinition, $config) {
                    if (array_key_exists('cache', $config)) {
                        $clientDefinition->addMethodCall(
                            'setCache',
                            [
                                new Reference($config['cache']['service']),
                                $config['cache']['ttl'],
                                $config['cache']['key_prefix'],
                            ]
                        );
                    }
                }
            );
        }
    }

    /**
     * loadClient
     *
     * @param ContainerBuilder $container   Container
     * @param string           $name        Service name
     * @param array            $config      Client config
     * @param array            $credentials Credentials accounts
     */
    protected function loadClient(ContainerBuilder $container, $name, array $config, array $credentials)
    {
        $className      = $container->getParameter('m6web_aws.client.class');
        $factoryService = $container->getParameter('m6web_aws.client_factory.name');
        $params         = [
            'service' => $config['service'],
            'config'  => !empty($config['credential']) ? $credentials[$config['credential']] : array(),
        ];

        if (!empty($config['region'])) {
            $params['region'] = $config['region'];
        }

        if (!empty($config['base_url'])) {
            $params['base_url'] = $config['base_url'];
        }

        $definition = new Definition($className, $params);
        $definition->setFactory([new Reference($factoryService), 'get']);

        $container->setDefinition(sprintf('m6web_aws.%s', $name), $definition);
    }

    /**
     * Loads a client with its proxy
     *
     * @param ContainerBuilder $container Container builder
     * @param array            $configs   Client config
     * @param string           $configKey Key of this element in the configuration (eg: 'sts', 'sqs', 'dynamodb')
     * @param Closure          $onCreate  Closure to add specific operations on client creation
     *
     * @return void
     */
    protected function loadProxyClient(ContainerBuilder $container, array $configs, $configKey, \Closure $onCreate = null)
    {
        $clientClassName = $container->getParameter('m6web_aws.'.$configKey.'.client.class');
        $proxyClassName  = $container->getParameter('m6web_aws.'.$configKey.'.proxy.class');

        foreach ($configs as $name => $config) {
            // Aws Client
            $awsClientName = sprintf('m6web_aws.%s', $config['client']);
            $params        = [
                'client' => new Reference($awsClientName),
            ];

            // M6 Client
            $clientDefinition = new Definition($clientClassName, $params);

            // If there is a defined closure to call on client creation
            if (is_callable($onCreate)) {
                $onCreate($clientDefinition, $config);
            }

            $clientName       = sprintf('m6web_aws.'.$configKey.'client.%s', $name);
            $container->setDefinition($clientName, $clientDefinition);

            // M6 Proxy
            $params = [
                'client' => new Reference($clientName),
            ];

            $proxyDefinition = new Definition($proxyClassName, $params);
            $proxyDefinition->addMethodCall(
                'setEventDispatcher',
                [new Reference('event_dispatcher'), 'M6Web\Bundle\AwsBundle\Event\Command']
            );
            $container->setDefinition(sprintf('m6web_aws.'.$configKey.'.%s', $name), $proxyDefinition);
        }
    }

    /**
     * loadS3
     *
     * @param ContainerBuilder $container Container
     * @param array            $configs   Client config
     */
    protected function loadS3(ContainerBuilder $container, array $configs)
    {
        if (!empty($configs['buckets'])) {
            foreach ($configs['buckets'] as $name => $config) {
                $this->loadBucket($container, $name, $config);
            }
        }
    }

    /**
     * loadBucket
     *
     * @param ContainerBuilder $container Container
     * @param string           $name      Service name
     * @param array            $config    Client config
     */
    protected function loadBucket(ContainerBuilder $container, $name, $config)
    {
        $className  = $container->getParameter('m6web_aws.bucket.class');
        $clientName = sprintf('m6web_aws.%s', $config['client']);
        $params     = [
            'client' => new Reference($clientName),
            'name'   => $config['name'],
        ];

        $definition = new Definition($className, $params);

        $container->setDefinition(sprintf('m6web_aws.bucket.%s', $name), $definition);
    }
}
