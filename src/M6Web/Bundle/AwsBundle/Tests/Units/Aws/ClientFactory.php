<?php

namespace M6Web\Bundle\AwsBundle\Tests\Units\Aws;

require_once __DIR__ . '/../../../../../../../vendor/autoload.php';

use atoum;
use M6Web\Bundle\AwsBundle\Aws\ClientFactory as BaseFactory;

/**
 * ClientFactory
 */
class ClientFactory extends atoum
{
    protected function createFactory($factoryClass = BaseFactory::AWS_FACTORY_CLASS, array $aliasKeys = array())
    {
        return new BaseFactory($factoryClass, $aliasKeys);
    }

    public function testAccessors()
    {
        $aliasKeys = [
            'signature_service',
            'signature_region',
            'curl_options',
            'request_options',
            'command_params'
        ];

        $this
            ->if($factory = $this->createFactory(BaseFactory::AWS_FACTORY_CLASS))
            ->then
                ->object($factory)
                    ->isInstanceOf('M6Web\Bundle\AwsBundle\Aws\ClientFactory')
                ->array($factory->getAliasKeys())
                    ->hasSize(0)
                ->object($factory->setAliasKeys($aliasKeys))
                    ->isInstanceOf('M6Web\Bundle\AwsBundle\Aws\ClientFactory')
                ->array($factory->getAliasKeys())
                    ->hasSize(5)
                    ->containsValues($aliasKeys);
    }

    public function testInvalidFactory()
    {
        $this
            ->exception(function() {
                new BaseFactory('stdClass');
            })
            ->isInstanceOf('\InvalidArgumentException');
    }

    public function testGet()
    {
        $this
            ->if($factory = $this->createFactory(BaseFactory::AWS_FACTORY_CLASS))
            ->then
                ->object($factory->get('S3', array()))
                    ->isInstanceOf('Aws\S3\S3Client')
                ->exception(function() use($factory) {
                    $factory->get('S4', array());
                })
                ->isInstanceOf('Guzzle\Service\Exception\ServiceNotFoundException');
    }

}
