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
        $loader->load('data_collector.yml');

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
            $this->loadSqs($container, $config['sqs']);
        }

        if (array_key_exists('dynamodb', $config)) {
            $this->loadDynamoDb($container, $config['dynamodb']);
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
        $params         = array(
            'service' => $config['service'],
            'config'  => $credentials[$config['credential']]
        );

        if (!empty($config['region'])) {
            $params['region'] = $config['region'];
        }

        if (!empty($config['base_url'])) {
            $params['base_url'] = $config['base_url'];
        }

        $definition = new Definition($className, $params);

        $definition
                    ->setFactoryService($factoryService)
                    ->setFactoryMethod('get');

        $container->setDefinition(sprintf('m6web_aws.%s', $name), $definition);
    }

    /**
     * loadDynamoDb
     *
     * @param ContainerBuilder $container Container
     * @param array            $configs   Client config
     */
    protected function loadDynamoDb(ContainerBuilder $container, array $configs)
    {
        $clientClassName = $container->getParameter('m6web_aws.dynamodb.client.class');
        $proxyClassName  = $container->getParameter('m6web_aws.dynamodb.proxy.class');

        foreach ($configs as $name => $config) {
            // AWS DynamoDb Client
            $awsClientName = sprintf('m6web_aws.%s', $config['client']);
            $params = [
                'client' => new Reference($awsClientName)
            ];

            // M6 DynamoDb Client
            $clientDefinition = new Definition($clientClassName, $params);

            if (array_key_exists('cache', $config)) {
                $clientDefinition->addMethodCall(
                    'setCache',
                    [
                        new Reference($config['cache']['service']),
                        $config['cache']['ttl'],
                        $config['cache']['key_prefix']
                    ]
                );
            }

            $clientName = sprintf('m6web_aws.dynamodbclient.%s', $name);
            $container->setDefinition($clientName, $clientDefinition);

            // M6 DynamoDb Client Proxy
            $params = [
                'client' => new Reference($clientName)
            ];

            $proxyDefinition = new Definition($proxyClassName, $params);
            $proxyDefinition->setScope(ContainerInterface::SCOPE_CONTAINER);
            $proxyDefinition->addMethodCall(
                'setEventDispatcher',
                [new Reference('event_dispatcher'), 'M6Web\Bundle\AwsBundle\Event\Command']
            );

            $container->setDefinition(sprintf('m6web_aws.dynamodb.%s', $name), $proxyDefinition);
        }
    }

    /**
     * loadSqs
     *
     * @param ContainerBuilder $container Container
     * @param array            $configs   Client config
     */
    protected function loadSqs(ContainerBuilder $container, array $configs)
    {
        $clientClassName = $container->getParameter('m6web_aws.sqs.client.class');
        $proxyClassName  = $container->getParameter('m6web_aws.sqs.proxy.class');

        foreach ($configs as $name => $config) {
            // Aws Sqs Client
            $awsClientName = sprintf('m6web_aws.%s', $config['client']);
            $params        = array(
                'client' => new Reference($awsClientName)
            );

            // M6 Sqs Client
            $clientDefinition = new Definition($clientClassName, $params);
            $clientName       = sprintf('m6web_aws.sqsclient.%s', $name);
            $container->setDefinition($clientName, $clientDefinition);

            // M6 Proxy Sqs Client
            $params = array(
                'client' => new Reference($clientName)
            );

            $proxyDefinition = new Definition($proxyClassName, $params);
            $proxyDefinition->setScope(ContainerInterface::SCOPE_CONTAINER);
            $proxyDefinition->addMethodCall(
                'setEventDispatcher',
                [new Reference('event_dispatcher'), 'M6Web\Bundle\AwsBundle\Event\Command']
            );
            $container->setDefinition(sprintf('m6web_aws.sqs.%s', $name), $proxyDefinition);
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
        $params     = array(
            'client' => new Reference($clientName),
            'name'   => $config['name']
        );

        $definition = new Definition($className, $params);

        $container->setDefinition(sprintf('m6web_aws.bucket.%s', $name), $definition);
    }

}
