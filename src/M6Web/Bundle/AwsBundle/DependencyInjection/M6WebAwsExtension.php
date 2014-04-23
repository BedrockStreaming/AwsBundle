<?php

namespace M6Web\Bundle\AwsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

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
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        if (!empty($config['aws_factory_class'])) {
            $container->setParameter('m6web_aws.aws_factory.class', $config['aws_factory_class']);
        }



        $credentials = $config['credentials'];
        $clients     = $config['clients'];

        foreach ($clients as $name => $client) {
            $this->loadClient($container, $name, $client, $credentials);
        }
    }

    /**
     * loadClient
     *
     * @param ContainerBuilder $container Container
     * @param array            $config    Client config
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

        $definition = new Definition($className, $params);

        $definition
                    ->setFactoryService($factoryService)
                    ->setFactoryMethod('get');

        $container->setDefinition(sprintf('m6web_aws.%s', $name), $definition);
    }

}
