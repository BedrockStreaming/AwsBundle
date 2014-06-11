<?php

namespace M6Web\Bundle\AwsBundle\Tests\Units\Aws\DynamoDb;

use atoum;
use M6Web\Bundle\AwsBundle\Aws\DynamoDb\Client as Base;
use Aws\DynamoDb\Model\Attribute;

/**
 * DynamoDb Client
 */
class Client extends atoum
{
    /**
     * Tests getItem method with required attributes only.
     */
    public function testGetItem()
    {
        $this
            ->if($awsClient = $this->createAwsClientMock())
            ->and($awsClient->getMockController()->getItem = null)
            ->and($client = new Base($awsClient))
            ->and(
                $client->getItem(
                    $tableName = uniqid(),
                    $key = ['id' => ['N' => 1234]]
                )
            )
            ->then
                ->mock($awsClient)
                    ->call('getItem')
                        ->withArguments([
                            'TableName'              => $tableName,
                            'Key'                    => $key,
                            'ConsistentRead'         => false,
                            'ReturnConsumedCapacity' => 'NONE'
                        ])
                        ->once();
    }

    /**
     * Tests putItem method with required attributes only.
     */
    public function testPutItem()
    {
        $this
            ->if($awsClient = $this->createAwsClientMock())
            ->and($awsClient->getMockController()->putItem = null)
            ->and($client = new Base($awsClient))
            ->and(
                $client->putItem(
                    $tableName = uniqid(),
                    $item = $client->formatAttributes([
                        'id'    => 1234,
                        'title' => 'Clip de test'
                    ], Attribute::FORMAT_PUT)
                )
            )
            ->then
                ->mock($awsClient)
                    ->call('putItem')
                        ->withArguments([
                            'TableName'                   => $tableName,
                            'Item'                        => [
                                'id'    => ['N' => 1234],
                                'title' => ['S' => 'Clip de test']
                            ],
                            'ConditionnalOperator'        => 'AND',
                            'ReturnValues'                => 'NONE',
                            'ReturnConsumedCapacity'      => 'NONE',
                            'ReturnItemCollectionMetrics' => 'NONE'
                        ])
                        ->once();
    }

    /**
     * Tests updateItem method with required attributes only + AttributeUpdates.
     */
    public function testUpdateItem()
    {
        $this
            ->if($awsClient = $this->createAwsClientMock())
            ->and($awsClient->getMockController()->updateItem = null)
            ->and($client = new Base($awsClient))
            ->and(
                $client->updateItem(
                    $tableName = uniqid(),
                    $key = ['id' => ['N' => uniqid()]],
                    $attributes = $client->formatAttributes([
                        'id'    => 1234,
                        'title' => 'Clip de test'
                    ], Attribute::FORMAT_UPDATE)
                )
            )
            ->then
                ->mock($awsClient)
                    ->call('updateItem')
                        ->withArguments([
                            'TableName'                   => $tableName,
                            'Key'                         => $key,
                            'AttributeUpdates'            => [
                                'id'    => ['Value' => ['N' => 1234]],
                                'title' => ['Value' => ['S' => 'Clip de test']]
                            ],
                            'ConditionnalOperator'        => 'AND',
                            'ReturnValues'                => 'NONE',
                            'ReturnConsumedCapacity'      => 'NONE',
                            'ReturnItemCollectionMetrics' => 'NONE'
                        ])
                        ->once();
    }

    /**
     * Tests deleteItem method with required attributes only.
     */
    public function testDeleteItem()
    {
        $this
            ->if($awsClient = $this->createAwsClientMock())
            ->and($awsClient->getMockController()->deleteItem = null)
            ->and($client = new Base($awsClient))
            ->and(
                $client->deleteItem(
                    $tableName = uniqid(),
                    $key = ['id' => ['N' => 1234]]
                )
            )
            ->then
                ->mock($awsClient)
                    ->call('deleteItem')
                        ->withArguments([
                            'TableName'                   => $tableName,
                            'Key'                         => $key,
                            'ConditionnalOperator'        => 'AND',
                            'ReturnValues'                => 'NONE',
                            'ReturnConsumedCapacity'      => 'NONE',
                            'ReturnItemCollectionMetrics' => 'NONE'
                        ])
                        ->once();
    }

    /**
     * Tests if cache is used on getItem().
     *
     * @return void
     */
    public function testCacheOnGetItem()
    {
        $this
            ->if($cache = $this->createCacheMock())
            ->and($awsClient = $this->createAwsClientMock())
            ->and($awsClient->getMockController()->getItem = null)
            ->and($client = new Base($awsClient))
            ->and($client->setCache($cache))
            ->and(
                $client->getItem(
                    $tableName = uniqid(),
                    ['id' => ['N' => $id = uniqid()], 'parent' => ['S' => $parent = uniqid()]]
                )
            )
            ->then
                ->mock($cache)
                    ->call('has')
                        ->once()
                    ->call('set')
                        ->once()
                    ->call('get')
                        ->never()
            ->if($cache->getMockController()->resetCalls())
            ->and(
                // New getItem() call to check if cache is used or not
                $client->getItem(
                    $tableName,
                    ['parent' => ['S' => $parent], 'id' => ['N' => $id]] // Elements are inverted to test consistent keys
                )
            )
            ->then
                ->mock($cache)
                    ->call('has')
                        ->once()
                    ->call('set')
                        ->never()
                    ->call('get')
                        ->once()
            ;
    }

    /**
     * Tests if cache is used on batchGetItem().
     *
     * @return void
     */
    public function testCacheOnBatchGetItem()
    {
        $this
            ->if($cache = $this->createCacheMock())
            ->and($awsClient = $this->createAwsClientMock())
            ->and($awsClient->getMockController()->batchGetItem = null)
            ->and($client = new Base($awsClient))
            ->and($client->setCache($cache))
            ->and(
                $client->batchGetItem(
                    [
                        ($tableName = uniqid()) => [
                            'Keys' => [
                                'id'     => ['N' => $id = uniqid()],
                                'parent' => ['S' => $parent = uniqid()]
                            ],
                            'AttributesToGet' => ['id', 'parent'],
                        ]
                    ]
                )
            )
            ->then
                ->mock($cache)
                    ->call('has')
                        ->once()
                    ->call('set')
                        ->once()
                    ->call('get')
                        ->never()
            ->if($cache->getMockController()->resetCalls())
            ->and(
                // New getItem() call to check if cache is used or not
                $client->batchGetItem(
                    [
                        // Elements are sorted in a different way, to test consistent cache key
                        $tableName => [
                            'AttributesToGet' => ['parent', 'id'],
                            'Keys' => [
                                'parent' => ['S' => $parent],
                                'id'     => ['N' => $id]
                            ],
                        ]
                    ]
                )
            )
            ->then
                ->mock($cache)
                    ->call('has')
                        ->once()
                    ->call('set')
                        ->never()
                    ->call('get')
                        ->once()
            ;
    }

    protected function createAwsClientMock()
    {
        $credentials = new \mock\Aws\Common\Credentials\CredentialsInterface();
        $signature   = new \mock\Aws\Common\Signature\SignatureInterface();
        $collection  = new \mock\Guzzle\Common\Collection();

        return new \mock\Aws\DynamoDb\DynamoDbClient($credentials, $signature, $collection);
    }

    protected function createCacheMock()
    {
        $mock = new \mock\M6Web\Bundle\AwsBundle\Cache\CacheInterface();
        $mock->values = [];

        $mock->getMockController()->set = function($key, $value, $ttl = null) {
            $this->values[$key] = $value;
        };

        $mock->getMockController()->has = function($key) {
            return array_key_exists($key, $this->values);
        };

        return $mock;
    }
}
