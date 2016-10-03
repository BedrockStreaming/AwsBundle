<?php

namespace M6Web\Bundle\AwsBundle\Event;

/**
 * Dispatcher interface
 */
interface DispatcherInterface
{
    /**
     * Set the sqs command associated with this event
     *
     * @param string $command The sqs command
     */
    public function setCommand($command);

    /**
     * set execution time
     *
     * @param float $v temps
     */
    public function setExecutionTime($v);

    /**
     * set the arguments
     *
     * @param array $v argus
     */
    public function setArguments($v);
}
