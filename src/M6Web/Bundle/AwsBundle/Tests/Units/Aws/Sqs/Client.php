<?php

namespace M6Web\Bundle\AwsBundle\Tests\Units\Aws\Sqs;

require_once __DIR__ . '/../../../../../../../../vendor/autoload.php';

use atoum;
use M6Web\Bundle\AwsBundle\Aws\Sqs\Client as Base;
use Aws\Sqs\Exception\SqsException;

/**
 * Client
 */
class Client extends atoum
{
    public function testConstruct()
    {

        $this
            ->if($clientSqs = $this->getClientSqs())
            ->and($client = new Base($clientSqs))
                ->object($client->getClient())
                    ->isIdenticalTo($clientSqs);
    }

    public function testGetQueue()
    {
        // Get queue ok
        $clientSqs = $this->getClientSqs();

        $this
            ->if($client = new Base($clientSqs))
            ->and($queueName = 'name')
            ->and($params = ['QueueName' => $queueName])
                ->string($client->getQueue($queueName))
                ->string($client->getQueue($queueName))
                    ->isEqualTo('queueUrl')
                    ->mock($clientSqs)
                        ->call('getQueueUrl')
                            ->withArguments($params)
                            ->once();

        // Get queue error
        $clientSqs->getMockController()->getQueueUrl = function() {
            throw new SqsException();
        };

        $this
            ->if($client = new Base($clientSqs))
            ->and($queueName = 'error')
                ->exception(
                     function() use ($client, $queueName) {
                        $client->getQueue($queueName);
                     });
    }

    public function testCreateQueue()
    {
        // Create ok
        $clientSqs = $this->getClientSqs();
        $clientSqs->getMockController()->createQueue = function() {
            $model = new \mock\Guzzle\Service\Resource\Model();
            $model->getMockController()->get = function() {
                return "queueUrl";
            };
            return $model;
        };

        $this
            ->if($client = new Base($clientSqs))
            ->and($queueName = 'name')
            ->and($queueAttr = ['key' => 'value'])
            ->and($params = ['QueueName' => $queueName, 'Attributes' => $queueAttr])
                ->variable($client->createQueue($queueName, $queueAttr))
                    ->mock($clientSqs)
                        ->call('createQueue')
                            ->withArguments($params)
                            ->once();

        // Create error
        $clientSqs->getMockController()->createQueue = function() {
            throw new SqsException();
        };

        $this
            ->if($client = new Base($clientSqs))
            ->and($queueName = 'name')
                ->exception(
                     function() use ($client, $queueName) {
                        $client->createQueue($queueName);
                     });
    }

    public function testDeleteQueue()
    {
        // Delete queue ok
        $clientSqs = $this->getClientSqs();
        $clientSqs->getMockController()->deleteQueue = function() {
            $model = new \mock\Guzzle\Service\Resource\Model();
            return $model;
        };

        $this
            ->if($client = new Base($clientSqs))
            ->and($queueName = 'name')
            ->and($params = ['QueueUrl' => 'queueUrl'])
                ->boolean($client->deleteQueue($queueName))
                    ->isTrue()
                    ->mock($clientSqs)
                        ->call('deleteQueue')
                            ->withArguments($params)
                            ->once();

        // Delete queue error
        $clientSqs->getMockController()->deleteQueue = function() {
            return null;
        };

        $this
            ->if($client = new Base($clientSqs))
            ->and($queueName = 'name')
                ->boolean($client->deleteQueue($queueName))
                    ->isFalse();
    }

    public function testSendMessage()
    {
        // Send Message ok
        $clientSqs = $this->getClientSqs();
        $clientSqs->getMockController()->sendMessage = function() {
            $model = new \mock\Guzzle\Service\Resource\Model();
            $model->getMockController()->get = function() {
                return "messageId";
            };
            return $model;
        };

        $this
            ->if($client = new Base($clientSqs))
            ->and($queueName = 'name')
            ->and($message = "Toto")
            ->and($delay = 1)
            ->and($messageAttr = ['key' => 'value'])
            ->and($params = [
                'QueueUrl' => 'queueUrl',
                'MessageBody' => $message,
                'DelaySeconds' => $delay,
                'MessageAttributes' => $messageAttr
                ])
                ->string($client->sendMessage($queueName, $message, $delay, $messageAttr))
                    ->isEqualTo('messageId')
                    ->mock($clientSqs)
                        ->call('sendMessage')
                            ->withArguments($params)
                            ->once();

        // Send message error
        $clientSqs->getMockController()->sendMessage = function() {
            return null;
        };

        $this
            ->if($client = new Base($clientSqs))
            ->and($queueName = 'name')
            ->and($message = "Toto")
                ->variable($client->sendMessage($queueName, $message))
                    ->isNull();
    }

    public function testReceiveMessage()
    {
        // Receive Message ok
        $clientSqs = $this->getClientSqs();
        $clientSqs->getMockController()->receiveMessage = function() {
            $model = new \mock\Guzzle\Service\Resource\Model();
            $model->getMockController()->get = function() {
                return array('Messages');
            };
            return $model;
        };

        $this
            ->if($client = new Base($clientSqs))
            ->and($queueName = 'name')
            ->and($maxNumberOfMessages = 5)
            ->and($waitTimeSeconds = 1)
            ->and($visibilityTimeout = 10)
            ->and($attributeNames = ['key' => 'value'])
            ->and($messageAttributeNames = ['key' => 'value'])
            ->and($params = [
                'QueueUrl' => 'queueUrl',
                'MaxNumberOfMessages' => $maxNumberOfMessages,
                'WaitTimeSeconds' => $waitTimeSeconds,
                'VisibilityTimeout' => $visibilityTimeout,
                'AttributeNames' => $attributeNames,
                'MessageAttributeNames' => $messageAttributeNames,
                ])
                ->array($client->receiveMessage(
                    $queueName,
                    $maxNumberOfMessages,
                    $waitTimeSeconds,
                    $visibilityTimeout,
                    $attributeNames,
                    $messageAttributeNames
                ))
                    ->isEqualTo(['Messages'])
                    ->mock($clientSqs)
                        ->call('receiveMessage')
                            ->withArguments($params)
                            ->once();

        // Receive message error
        $clientSqs->getMockController()->receiveMessage = function() {
            return null;
        };

        $this
            ->if($client = new Base($clientSqs))
            ->and($queueName = 'name')
                ->variable($client->receiveMessage($queueName))
                    ->isNull();
    }

    public function testDeleteMessage()
    {
        // Delete Message ok
        $clientSqs = $this->getClientSqs();
        $clientSqs->getMockController()->deleteMessage = function() {
            $model = new \mock\Guzzle\Service\Resource\Model();
            return $model;
        };

        $this
            ->if($client = new Base($clientSqs))
            ->and($queueName = 'name')
            ->and($receiveHandle = 'receiveHandleId')
            ->and($params = [
                'QueueUrl' => 'queueUrl',
                'ReceiptHandle' => $receiveHandle,
                ])
                ->boolean($client->deleteMessage($queueName, $receiveHandle))
                    ->isTrue()
                    ->mock($clientSqs)
                        ->call('deleteMessage')
                            ->withArguments($params)
                            ->once();

        // Delete message error
        $clientSqs->getMockController()->deleteMessage = function() {
            return null;
        };

        $this
            ->if($client = new Base($clientSqs))
            ->and($queueName = 'name')
            ->and($receiveHandle = 'receiveHandleId')
                ->boolean($client->deleteMessage($queueName, $receiveHandle))
                    ->isFalse();
    }

    public function getClientSqs()
    {
        $this->mockGenerator->shuntParentClassCalls();
        $clientSqs = new \mock\Aws\Sqs\SqsClient(
            new \mock\Aws\Common\Credentials\CredentialsInterface(),
            new \mock\Aws\Common\Signature\SignatureInterface(),
            new \mock\Guzzle\Common\Collection()
        );

        $clientSqs->getMockController()->getQueueUrl = function() {
            $model = new \mock\Guzzle\Service\Resource\Model();
            $model->getMockController()->get = function() {
                return "queueUrl";
            };
            return $model;
        };

        return $clientSqs;
    }
}
