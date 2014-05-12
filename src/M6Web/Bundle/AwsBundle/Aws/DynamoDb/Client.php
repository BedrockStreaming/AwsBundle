<?php

namespace M6Web\Bundle\AwsBundle\Aws\DynamoDb;

use Aws\DynamoDb\DynamoDbClient;

/**
 * DynamoDb Client
 */
class Client
{
    const CAPACITY_INDEXES = 'INDEXES';
    const CAPACITY_TOTAL   = 'TOTAL';
    const CAPACITY_NONE    = 'NONE';

    const METRICS_SIZE = 'SIZE';
    const METRICS_NONE = 'NONE';

    const COND_AND = 'AND';
    const COND_OR  = 'OR';

    const RETURN_NONE        = 'NONE';
    const RETURN_ALL_OLD     = 'ALL_OLD';
    const RETURN_UPDATED_OLD = 'UPDATED_OLD';
    const RETURN_ALL_NEW     = 'ALL_NEW';
    const RETURN_UPDATED_NEW = 'UPDATED_NEW';

    const SELECT_ALL_ATTRIBUTES           = 'ALL_ATTRIBUTES';
    const SELECT_ALL_PROJECTED_ATTRIBUTES = 'ALL_PROJECTED_ATTRIBUTES';
    const SELECT_SPECIFIC_ATTRIBUTES      = 'SPECIFIC_ATTRIBUTES';
    const SELECT_COUNT                    = 'COUNT';

    /**
     * @var DynamoDbClient
     */
    private $client;

    /**
     * __construct
     *
     * @param DynamoDbClient $client Aws DynamoDb Client
     */
    public function __construct(DynamoDbClient $client)
    {
        $this->client = $client;
    }

    /**
     * Direct access to the DynamoDb client
     * 
     * @return DynamoDbClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Formats a value as a DynamoDB attribute
     * 
     * @param mixed  $value  The value to format for DynamoDB.
     * @param string $format The type of format (e.g. put, update).
     * 
     * @return array The formatted value
     */
    public function formatValue($value, $format = Aws\DynamoDB\Attribute::FORMAT_PUT)
    {
        return $this->client->formatValue($value, $format);
    }

    /**
     * Formats an array of values as DynamoDB attributes.
     * 
     * @param array  $values The values to format for DynamoDB.
     * @param string $format The type of format (e.g. put, update).
     * 
     * @return array The formatted values
     */
    public function formatAttributes(array $values, $format = Aws\DynamoDB\Attribute::FORMAT_PUT)
    {
        return $this->client->formatAttributes($values, $format);
    }

    /**
     * Calculate the amount of time needed for an exponential backoff to wait before retrying a request
     * 
     * @param integer $retries Number of retries
     * 
     * @return float Returnes the amount of time to wait in seconds.
     */
    public function calculateRetryDelay($retries)
    {
        $class = get_class($this->client);

        return $class::calculateRetryDelay($retries);
    }

    /**
     * Convenience method for instantiating and registering the DynamoDB Session handler with this DynamoDB client object.
     * 
     * @see http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.DynamoDb.Session.SessionHandler.html#_factory
     * 
     * @param array $config Array of options for the session handler factory
     * 
     * @return Aws\DynamoDb\Session\SessionHandler
     */
    public function registerSessionHandler(array $config = [])
    {
        return $this->client->registerSessionHandler($config);
    }

    /**
     * Executes the BatchGetItem operation.
     *
     * @see http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.DynamoDb.DynamoDbClient.html#_batchGetItem
     *
     * @param array  $requestItems           Associative array of <TableName> keys mapping to (associative-array) values.
     * @param string $returnConsumedCapacity Sets consumed capacity return mode.
     * 
     * @return Guzzle\Service\Resource\Model
     */
    public function batchGetItem(array $requestItems, $returnConsumedCapacity = self::CAPACITY_NONE)
    {
        return $this->client->batchGetItem(
            [
                'RequestItems'           => $requestItems,
                'ReturnConsumedCapacity' => $returnConsumedCapacity
            ]
        );
    }

    /**
     * Executes the BatchWriteItem operation.
     *
     * @see http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.DynamoDb.DynamoDbClient.html#_batchWriteItem
     * 
     * @param array  $requestItems                Associative array of <TableName> keys mapping to (array<associative-array>) values.
     * @param string $returnConsumedCapacity      Sets consumed capacity return mode.
     * @param string $returnItemCollectionMetrics If set to SIZE, statistics about item collections, if any, that were modified during the operation are returned in the response.
     * 
     * @return Guzzle\Service\Resource\Model
     */
    public function batchWriteItem(array $requestItems, $returnConsumedCapacity = self::CAPACITY_NONE, $returnItemCollectionMetrics = self::METRICS_NONE)
    {
        return $this->client->batchWriteItem(
            [
                'RequestItems'                => $requestItems,
                'ReturnConsumedCapacity'      => $returnConsumedCapacity,
                'ReturnItemCollectionMetrics' => $returnItemCollectionMetrics
            ]
        );
    }

    /**
     * Executes the CreateTable operation.
     *
     * @see http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.DynamoDb.DynamoDbClient.html#_createTable
     *
     * @param array  $attributeDefinitions   An array of attributes that describe the key schema for the table and indexes.
     * @param string $tableName              The name of the table to create.
     * @param array  $keySchema              Specifies the attributes that make up the primary key for a table or an index
     * @param array  $provisionedThroughput  Represents the provisioned throughput settings for a specified table or index.
     * @param array  $localSecondaryIndexes  One or more local secondary indexes (the maximum is five) to be created on the table.
     * @param array  $globalSecondaryIndexes One or more global secondary indexes (the maximum is five) to be created on the table.
     * 
     * @return Guzzle\Service\Resource\Model
     */
    public function createTable(array $attributeDefinitions, $tableName, array $keySchema, array $provisionedThroughput, array $localSecondaryIndexes = null, array $globalSecondaryIndexes = null)
    {
        $args = [
            'AttributeDefinitions'   => $attributeDefinitions,
            'TableName'              => $tableName,
            'KeySchema'              => $keySchema,
            'ProvisionedThroughput'  => $provisionedThroughput
        ];

        if ($localSecondaryIndexes !== null) {
            $args['LocalSecondaryIndexes'] = $localSecondaryIndexes;
        }

        if ($globalSecondaryIndexes !== null) {
            $args['GlobalSecondaryIndexes'] = $globalSecondaryIndexes;
        }

        return $this->client->createTable($args);
    }

    /**
     * Executes the DeleteItem operation.
     *
     * @see http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.DynamoDb.DynamoDbClient.html#_deleteItem
     * 
     * @param string $tableName                   The name of the table from which to delete the item.
     * @param array  $key                         Associative array of <AttributeName> keys mapping to (associative-array) values.
     * @param array  $expected                    This is the conditional block for the DeleteItem operation. All the conditions must be met for the operation to succeed.
     * @param string $conditionnalOperator        Operator between each condition of $expected argument.
     * @param string $returnValues                Use ReturnValues if you want to get the item attributes as they appeared before they were deleted.
     * @param string $returnConsumedCapacity      Sets consumed capacity return mode.
     * @param string $returnItemCollectionMetrics If set to SIZE, statistics about item collections, if any, that were modified during the operation are returned in the response.
     * 
     * @return Guzzle\Service\Resource\Model
     */
    public function deleteItem($tableName, array $key, array $expected = [], $conditionnalOperator = self::COND_AND, $returnValues = self::RETURN_NONE, $returnConsumedCapacity = self::CAPACITY_NONE, $returnItemCollectionMetrics = self::METRICS_NONE)
    {
        return $this->client->deleteItem(
            [
                'TableName'                   => $tableName,
                'Key'                         => $key,
                'Expected'                    => $expected,
                'ConditionnalOperator'        => $conditionnalOperator,
                'ReturnValues'                => $returnValues,
                'ReturnConsumedCapacity'      => $returnConsumedCapacity,
                'ReturnItemCollectionMetrics' => $returnItemCollectionMetrics
            ]
        );
    }

    /**
     * Executes the DeleteTable operation.
     *
     * @see http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.DynamoDb.DynamoDbClient.html#_deleteTable
     * 
     * @param string $tableName Name of the table to delete
     * 
     * @return Guzzle\Service\Resource\Model
     */
    public function deleteTable($tableName)
    {
        return $this->client->deleteTable(['TableName' => $tableName]);
    }

    /**
     * Returns information about the table, including the current status of the table,
     * when it was created, the primary key schema, and any indexes on the table.
     *
     * @see http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.DynamoDb.DynamoDbClient.html#_describeTable
     *
     * @param string $tableName Name of the table to describe
     * 
     * @return Guzzle\Service\Resource\Model
     */
    public function describeTable($tableName)
    {
        return $this->client->describeTable(['TableName' => $tableName]);
    }

    /**
     * The GetItem operation returns a set of attributes for the item with the given primary key.
     * If there is no matching item, GetItem does not return any data.
     *
     * @see http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.DynamoDb.DynamoDbClient.html#_getItem
     * 
     * @param string  $tableName              The name of the table containing the requested item.
     * @param array   $key                    Associative array of <AttributeName> keys mapping to (associative-array) values.
     * @param array   $attributesToGet        The names of one or more attributes to retrieve. If no attribute names are specified, then all attributes will be returned.
     * @param boolean $consistentRead         If set to true, then the operation uses strongly consistent reads; otherwise, eventually consistent reads are used.
     * @param string  $returnConsumedCapacity Sets consumed capacity return mode.
     * 
     * @return Guzzle\Service\Resource\Model
     */
    public function getItem($tableName, array $key, array $attributesToGet = [], $consistentRead = false, $returnConsumedCapacity = self::CAPACITY_NONE)
    {
        return $this->client->getItem(
            [
                'TableName'              => $tableName,
                'Key'                    => $key,
                'AttributesToGet'        => $attributesToGet,
                'ConsistentRead'         => $consistentRead,
                'ReturnConsumedCapacity' => $returnConsumedCapacity
            ]
        );
    }

    /**
     * Returns an array of all the tables associated with the current account and endpoint.
     * 
     * @see http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.DynamoDb.DynamoDbClient.html#_listTables
     *
     * @param string  $exclusiveStartTableName Name of the table that starts the list.
     * @param integer $limit                   A maximum number of tables to return.
     * 
     * @return  Guzzle\Service\Resource\Model
     */
    public function listTables($exclusiveStartTableName = null, $limit = null)
    {
        $params = [];

        if (is_string($exclusiveStartTableName)) {
            $params['ExclusiveStartTableName'] = $exclusiveStartTableName;
        }

        if (is_numeric($limit)) {
            $params['Limit'] = $limit;
        }

        return $this->client->listTables($params);
    }

    /**
     * Creates a new item, or replaces an old item with a new item. 
     * If an item already exists in the specified table with the same primary key, 
     * the new item completely replaces the existing item. 
     * You can perform a conditional put (insert a new item if one with the specified primary key doesn't exist), 
     * or replace an existing item if it has certain attribute values.
     *
     * @see http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.DynamoDb.DynamoDbClient.html#_putItem
     *
     * @param string $tableName                   The name of the table to contain the item.
     * @param array  $item                        Associative array of <AttributeName> keys mapping to (associative-array) values.
     * @param array  $expected                    This is the conditional block for the PutItem operation. All the conditions must be met for the operation to succeed.
     * @param string $conditionnalOperator        Operator between each condition of $expected argument.
     * @param string $returnValues                Use ReturnValues if you want to get the item attributes as they appeared before they were updated.
     * @param string $returnConsumedCapacity      Sets consumed capacity return mode.
     * @param string $returnItemCollectionMetrics If set to SIZE, statistics about item collections, if any, that were modified during the operation are returned in the response.
     * 
     * @return Guzzle\Service\Resource\Model
     */
    public function putItem($tableName, array $item, array $expected = [], $conditionnalOperator = self::COND_AND, $returnValues = self::RETURN_NONE, $returnConsumedCapacity = self::CAPACITY_NONE, $returnItemCollectionMetrics = self::METRICS_NONE)
    {
        return $this->client->putItem(
            [
                'TableName'                   => $tableName,
                'Item'                        => $item,
                'Expected'                    => $expected,
                'ConditionnalOperator'        => $conditionnalOperator,
                'ReturnValues'                => $returnValues,
                'ReturnConsumedCapacity'      => $returnConsumedCapacity,
                'ReturnItemCollectionMetrics' => $returnItemCollectionMetrics
            ]
        );
    }

    /* TODO */
    public function query() {}
    public function scan() {}

    /**
     * Edits an existing item's attributes, or inserts a new item if it does not already exist. 
     * You can put, delete, or add attribute values. 
     * You can also perform a conditional update (insert a new attribute name-value pair if it doesn't exist, or replace an existing name-value pair if it has certain expected attribute values).
     *
     * @see http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.DynamoDb.DynamoDbClient.html#_updateItem
     * 
     * @param string $tableName                   The name of the table containing the item to update.
     * @param array  $key                         Associative array of <AttributeName> keys mapping to (associative-array) values.
     * @param array  $attributeUpdates            The names of attributes to be modified, the action to perform on each, and the new value for each.
     * @param array  $expected                    A map of attribute/condition pairs. This is the conditional block for the UpdateItem operation. All the conditions must be met for the operation to succeed.
     * @param string $conditionnalOperator        Operator between each condition of $expected argument.
     * @param string $returnValues                Use ReturnValues if you want to get the item attributes as they appeared either before or after they were updated.
     * @param string $returnConsumedCapacity      Sets consumed capacity return mode.
     * @param string $returnItemCollectionMetrics If set to SIZE, statistics about item collections, if any, that were modified during the operation are returned in the response.
     * 
     * @return Guzzle\Service\Resource\Model
     */
    public function updateItem($tableName, array $key, array $attributeUpdates = [], array $expected = [], $conditionnalOperator = self::COND_AND, $returnValues = self::RETURN_NONE, $returnConsumedCapacity = self::CAPACITY_NONE, $returnItemCollectionMetrics = self::METRICS_NONE)
    {
        return $this->client->updateItem(
            [
                'TableName'                   => $tableName,
                'Key'                         => $key,
                'AttributeUpdates'            => $attributeUpdates,
                'Expected'                    => $expected,
                'ConditionnalOperator'        => $conditionnalOperator,
                'ReturnValues'                => $returnValues,
                'ReturnConsumedCapacity'      => $returnConsumedCapacity,
                'ReturnItemCollectionMetrics' => $returnItemCollectionMetrics
            ]
        );
    }

    /* TODO */
    public function updateTable() {}
    public function waitUntilTableExists() {}
    public function waitUntilTableNotExists() {}
    public function getBatchGetItemIterator() {}
    public function getListTablesIterator() {}
    public function getQueryIterator() {}
    public function getScanIterator() {}
}