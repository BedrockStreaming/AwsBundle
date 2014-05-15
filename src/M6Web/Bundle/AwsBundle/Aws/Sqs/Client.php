<?php

namespace M6Web\Bundle\AwsBundle\Aws\Sqs;

use Aws\Sqs\SqsClient;
use Guzzle\Service\Resource\Model;

/**
 * Sqs Client
 */
class Client
{
/**
     * @var SqsClient
     */
    private $client;

    /**
     * __construct
     *
     * @param SqsClient $client Aws SqsClient Client
     */
    public function __construct(SqsClient $client)
    {
        $this->client = $client;
    }

    /**
     * Direct access to the SqsClient client
     *
     * @return SqsClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Creates a new queue, or returns the URL of an existing one. When you request CreateQueue,
     * you provide a name for the queue. To successfully create a new queue,
     * you must provide a name that is unique within the scope of your own queues.
     *
     * For more information, please see :
     * http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.Sqs.SqsClient.html#_createQueue
     *
     * @param string $name
     * @param array  $attributes
     *
     * @return string|null
     */
    public function createQueue($name, array $attributes = array())
    {
        $result = $this->client->createQueue([
            'QueueName' => $name,
            'Attributes' => $attributes
        ]);

        if ($result instanceof Model) {
            return $result->get('QueueUrl');
        }

        return null;
    }

    /**
     * Returns the URL of an existing queue.
     * This action provides a simple way to retrieve the URL of an Amazon SQS queue.
     *
     * For more information, please see :
     * http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.Sqs.SqsClient.html#_getQueueUrl
     *
     * @param string $name
     *
     * @return string|null
     */
    public function getQueue($name)
    {
        $result = $this->client->getQueueUrl(['QueueName' => $name]);
        if ($result instanceof Model) {
            return $result->get('QueueUrl');
        }

        return null;
    }

    /**
     * Deletes the queue specified by the queue URL, regardless of whether the queue is empty.
     * If the specified queue does not exist, Amazon SQS returns a successful response.
     * Use DeleteQueue with care; once you delete your queue, any messages in the queue are no longer available.
     *
     * For more information, please see :
     * http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.Sqs.SqsClient.html#_deleteQueue
     *
     * @param string $name
     *
     * @return boolean
     */
    public function deleteQueue($name)
    {
        $result = $this->client->deleteQueue(['QueueName' => $name]);
        if ($result instanceof Model) {
            return true;
        }

        return false;
    }

    /**
     * Delivers a message to the specified queue and return the messageId
     *
     * For more information, please see :
     * http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.Sqs.SqsClient.html#_sendMessage
     *
     * @param string  $queue
     * @param string  $message
     * @param integer $delay
     * @param array   $messageAttributes
     *
     * @return string|null
     */
    public function sendMessage($queue, $message, $delay = 0, array $messageAttributes = array())
    {
        $args = [
            'QueueUrl' => $queue,
            'MessageBody' => $message,
            'DelaySeconds' => $delay,
        ];

        if (!empty($messageAttributes)) {
            $args['MessageAttributes'] = $messageAttributes;
        }

        $result = $this->client->sendMessage($args);

        if ($result instanceof Model) {
            return $result->get('MessageId');
        }

        return null;
    }

    /**
     * Retrieves one or more messages from the specified queue.
     * Long poll support is enabled by using the WaitTimeSeconds parameter.
     * For more information, see Amazon SQS Long Poll in the Amazon SQS Developer Guide.
     *
     * The receipt handle is the identifier you must provide when deleting the message.
     * For more information, see Queue and Message Identifiers in the Amazon SQS Developer Guide.
     *
     * For more information, please see :
     * http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.Sqs.SqsClient.html#_receiveMessage
     *
     * @param string  $queue
     * @param integer $maxNumberOfMessages
     * @param integer $waitTimeSeconds
     * @param integer $visibilityTimeout
     * @param array   $attributeNames
     * @param array   $messageAttributeNames
     *
     * @return Array|null
     */
    public function receiveMessage(
        $queue,
        $maxNumberOfMessages = 1,
        $waitTimeSeconds = 0,
        $visibilityTimeout = null,
        array $attributeNames = array(),
        array $messageAttributeNames = array()
    )
    {
        $args = [
            'QueueUrl' => $queue,
            'MaxNumberOfMessages' => $maxNumberOfMessages,
            'WaitTimeSeconds' => $waitTimeSeconds
        ];

        if ($visibilityTimeout !== null) {
            $args['VisibilityTimeout'] = $visibilityTimeout;
        }

        if (!empty($attributeNames)) {
            $args['AttributeNames'] = $attributeNames;
        }

        if (!empty($messageAttributeNames)) {
            $args['MessageAttributeNames'] = $attributeNames;
        }

        $result = $this->client->receiveMessage($args);
        if ($result instanceof Model) {
            return $result->get('Messages');
        }

        return null;
    }

    /**
     * Deletes the specified message from the specified queue.
     * You specify the message by using the message's receipt handle and
     * not the message ID you received when you sent the message.
     * Even if the message is locked by another reader due to the visibility timeout setting,
     * it is still deleted from the queue.
     *
     * If you leave a message in the queue for longer than the queue's configured retention period,
     * Amazon SQS automatically deletes it.
     *
     * For more information, please see :
     * http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.Sqs.SqsClient.html#_deleteMessage
     *
     * @param string $queue
     * @param string $receiveHandle
     *
     * @return boolean
     */
    public function deleteMessage($queue, $receiveHandle)
    {
        $result = $this->client->deleteMessage([
            'QueueUrl' => $queue,
            'ReceiptHandle' => $receiveHandle
        ]);

        if ($result instanceof Model) {
            return true;
        }

        return false;
    }
}