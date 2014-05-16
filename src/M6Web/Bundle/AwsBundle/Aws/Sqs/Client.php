<?php

namespace M6Web\Bundle\AwsBundle\Aws\Sqs;

use Aws\Sqs\SqsClient;
use Guzzle\Service\Resource\Model;
use Aws\Sqs\Exception\SqsException;

/**
 * Sqs Client
 */
class Client
{
/**
     **
     * @var SqsClient
     */
    private $client;

    /**
     * @var Array
     */
    protected $queues;

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
     * Creates a new queue. When you request CreateQueue,
     * you provide a name for the queue. To successfully create a new queue,
     * you must provide a name that is unique within the scope of your own queues.
     *
     * For more information, please see :
     * http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.Sqs.SqsClient.html#_createQueue
     *
     * @param string $name       The name for the queue to be created. Maximum 80 characters; alphanumeric characters, hyphens (-), and underscores (_) are allowed.
     * @param array  $attributes Associative array of <QueueAttributeName> keys mapping to (string) values. Each array key should be changed to an appropriate <QueueAttributeName>.
     *
     * @return void
     * @throws SqsException
     */
    public function createQueue($queueId, array $attributes = array())
    {
        $result = $this->client->createQueue([
            'QueueName' => $queueId,
            'Attributes' => $attributes
        ]);

        $this->queues[$queueId] = $result->get('QueueUrl');
    }

    /**
     * Returns the URL of an existing queue.
     * This action provides a simple way to retrieve the URL of an Amazon SQS queue.
     *
     * For more information, please see :
     * http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.Sqs.SqsClient.html#_getQueueUrl
     *
     * @param string $queueId The name of the queue whose URL must be fetched.
     *
     * @return string
     * @throws SqsException
     */
    public function getQueue($queueId)
    {
        if (!isset($this->queues[$queueId])) {
            $this->queues[$queueId] = $this->client->getQueueUrl(['QueueName' => $queueId])->get('QueueUrl');
        }

        return $this->queues[$queueId];
    }

    /**
     * Deletes the queue specified by the queue name, regardless of whether the queue is empty.
     * If the specified queue does not exist, Amazon SQS returns a successful response.
     * Use DeleteQueue with care; once you delete your queue, any messages in the queue are no longer available.
     *
     * For more information, please see :
     * http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.Sqs.SqsClient.html#_deleteQueue
     *
     * @param string $queue The URL of the Amazon SQS queue to take action on.
     *
     * @return boolean
     * @throws SqsException
     */
    public function deleteQueue($queueId)
    {
        $result = $this->client->deleteQueue(['QueueUrl' => $this->getQueue($queueId)]);
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
     * @param string  $queueId           The name of the Amazon SQS queue to take action on.
     * @param string  $message           The message to send. String maximum 256 KB in size. For a list of allowed characters, see the preceding important note.
     * @param integer $delay             The number of seconds (0 to 900 - 15 minutes) to delay a specific message. Messages with a positive DelaySeconds value become available for processing after the delay time is finished. If you don't specify a value, the default value for the queue applies.
     * @param array   $messageAttributes Associative array of <String> keys mapping to (associative-array) values. Each array key should be changed to an appropriate <String>.
     *
     * @return string|null
     * @throws SqsException
     */
    public function sendMessage($queueId, $message, $delay = 0, array $messageAttributes = array())
    {
        $args = [
            'QueueUrl' => $this->getQueue($queueId),
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
     * @param string  $queueId               The name of the Amazon SQS queue to take action on.
     * @param integer $maxNumberOfMessages   The maximum number of messages to return. Amazon SQS never returns more messages than this value but may return fewer. All of the messages are not necessarily returned.
     * @param integer $waitTimeSeconds       The duration (in seconds) for which the call will wait for a message to arrive in the queue before returning. If a message is available, the call will return sooner than WaitTimeSeconds.
     * @param integer $visibilityTimeout     The duration (in seconds) that the received messages are hidden from subsequent retrieve requests after being retrieved by a ReceiveMessage request.
     * @param array   $attributeNames        A list of attributes that need to be returned along with each message.
     * @param array   $messageAttributeNames A list of message attributes that need to be returned along with each message.
     *
     * @return Array|null
     * @throws SqsException
     */
    public function receiveMessage(
        $queueId,
        $maxNumberOfMessages = 1,
        $waitTimeSeconds = 0,
        $visibilityTimeout = null,
        array $attributeNames = array(),
        array $messageAttributeNames = array()
    )
    {
        $args = [
            'QueueUrl' => $this->getQueue($queueId),
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
     * @param string $queueId       The URL of the Amazon SQS queue to take action on.
     * @param string $receiveHandle The receipt handle associated with the message to delete.
     *
     * @return boolean
     * @throws SqsException
     */
    public function deleteMessage($queueId, $receiveHandle)
    {
        $result = $this->client->deleteMessage([
            'QueueUrl' => $this->getQueue($queueId),
            'ReceiptHandle' => $receiveHandle
        ]);

        if ($result instanceof Model) {
            return true;
        }

        return false;
    }
}