<?php

namespace M6Web\Bundle\AwsBundle\Aws;

/**
 * ClientFactory
 */
class ClientFactory
{
    /**
     * @var string
     */
    const AWS_FACTORY_CLASS = 'Aws\Common\Aws';

    /**
     * @var array
     */
    protected $aliasKeys;

    /**
     * __construct
     *
     * @param string $factoryClass Factory class name
     * @param array  $aliasKeys    key list
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($factoryClass, array $aliasKeys = array())
    {
        if (!$this->testFactoryClass($factoryClass)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'factoryClass extend or be "%s"',
                    self::AWS_FACTORY_CLASS
                )
            );
        }

        $this->factoryClass = $factoryClass;
        $this->aliasKeys    = $aliasKeys;
    }

    /**
     * testFactoryClass
     *
     * @param string $factoryClass Factory class name
     *
     * @return boolean
     */
    protected function testFactoryClass($factoryClass)
    {
        if ($factoryClass == self::AWS_FACTORY_CLASS) {
            return true;
        }

        $reflection = new \ReflectionClass($factoryClass);

        if ($reflection->isSubclassOf(self::AWS_FACTORY_CLASS)) {
            return true;
        }

        return false;
    }

    /**
     * filterAliasKey
     *
     * @param string $name Config name
     *
     * @return string
     */
    protected function filterAliasKey($name)
    {
        if (in_array($name, $this->aliasKeys)) {
            return str_replace('_', '.', $name);
        }

        return $name;
    }

    /**
     * get
     *
     * @param string $service Aws Service alias
     * @param array  $config  Base config client
     *
     * @return AwsClient
     */
    public function get($service, array $config)
    {
        $params = array();

        foreach ($config as $name => $value) {
            $params[$this->filterAliasKey($name)] = $value;
        }

        $factory = $this->factoryClass;
        $aws     = $factory::factory($params);

        return $aws->get($service);
    }

    /**
     * setAliasKeys
     *
     * @param array $aliasKeys Alias key list
     *
     * @return ClientFactory
     */
    public function setAliasKeys(array $aliasKeys)
    {
        $this->aliasKeys = $aliasKeys;

        return $this;
    }

    /**
     * getAliasKeys
     *
     * @return array
     */
    public function getAliasKeys()
    {
        return $this->aliasKeys;
    }

}
