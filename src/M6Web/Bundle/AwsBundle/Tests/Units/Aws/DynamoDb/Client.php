<?php

namespace M6Web\Bundle\AwsBundle\Tests\Units\Aws\DynamoDb;

require_once __DIR__ . '/../../../../../../../../vendor/autoload.php';

use atoum;
use M6Web\Bundle\AwsBundle\Aws\DynamoDb\Client as Base;

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
                    $item = [
                        'id'    => 1234,
                        'title' => 'Clip de test'
                    ]
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
                    $attributes = [
                        'id'    => 1234,
                        'title' => 'Clip de test'
                    ]
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

    protected function createAwsClientMock()
    {
        $credentials = new \mock\Aws\Common\Credentials\CredentialsInterface();
        $signature   = new \mock\Aws\Common\Signature\SignatureInterface();
        $collection  = new \mock\Guzzle\Common\Collection();

        return new \mock\Aws\DynamoDb\DynamoDbClient($credentials, $signature, $collection);
    }
}
