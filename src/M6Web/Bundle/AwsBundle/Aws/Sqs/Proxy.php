<?php

namespace M6Web\Bundle\AwsBundle\Aws\Sqs;

use Aws\Sqs\Exception\SqsException;
use M6Web\Bundle\AwsBundle\Event\Dispatchable;

/**
 * Sqs Proxy Client
 */
class Proxy
{
    /**
     **
     * @var Client
     */
    private $client;

    /**
     * Event dispatcher
     *
     * @var Object
     */
    protected $eventDispatcher = null;

    /**
     * Class of the event notifier
     *
     * @var string
     */
    protected $eventClass = null;

    /**
     * __construct
     *
     * @param Client $client AwsBundle Client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Direct access to the client
     *
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Notify an event to the event dispatcher
     *
     * @param string $command   The command name
     * @param array  $arguments args of the command
     * @param int    $time      exec time
     *
     * @return void
     */
    public function notifyEvent($command, $arguments, $time = 0)
    {
        if ($this->eventDispatcher) {
            $className = $this->eventClass;

            $event = new $className();
            $event->setCommand($command);
            $event->setExecutionTime($time);
            $event->setArguments($arguments);

            $this->eventDispatcher->dispatch('sqs.command', $event);
        }
    }

    /**
     * Set an event dispatcher to notify redis command
     *
     * @param Object $eventDispatcher The eventDispatcher object, which implement the notify method
     * @param string $eventClass      The event class used to create an event and send it to the event dispatcher
     *
     * @return void
     *
     */
    public function setEventDispatcher($eventDispatcher, $eventClass)
    {
        if (!is_object($eventDispatcher) || !method_exists($eventDispatcher, 'dispatch')) {
            throw new Exception("The EventDispatcher must be an object and implement a dispatch method");
        }

        $class = new \ReflectionClass($eventClass);
        if (!$class->implementsInterface('\M6Web\Bundle\AwsBundle\Event\DispatcherInterface')) {
            throw new Exception("The Event class : ".$eventClass." must implement Dispatchable");
        }

        $this->eventDispatcher = $eventDispatcher;
        $this->eventClass      = $eventClass;
    }

    /**
     *  Magic method to the Sqs client
     *
     * @param string $name      method name
     * @param array  $arguments method arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if ($sqs = $this->getClient()) {
            $start = microtime(true);
            $ret = call_user_func_array(array($sqs, $name), $arguments);
            $this->notifyEvent($name, $arguments, microtime(true) - $start);

            return $ret;
        } else {
            throw new Exception("Cant connect to Sqs");
        }
    }
}