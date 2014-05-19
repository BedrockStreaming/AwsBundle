<?php

namespace M6Web\Bundle\AwsBundle\Event;

use Symfony\Component\EventDispatcher\Event as SymfonyEvent;
use M6Web\Bundle\AwsBundle\Event\Dispatchable;

/**
 * Command Event
 */
class Command extends SymfonyEvent implements DispatcherInterface
{
    /**
     * @var integer
     */
    protected $executionTime = 0;

    /**
     * @var string
     */
    protected $command;

    /**
     * @var array
     */
    protected $arguments;

    /**
     * Set the command associated with this event
     * @param string $command The command
     */
    public function setCommand($command)
    {
        $this->command = $command;
    }

    /**
     * Get the command associated with this event
     * @return string the command
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * set the arguments
     * @param array $v argus
     */
    public function setArguments($v)
    {
        $this->arguments = $v;
    }

    /**
     * get the arguments
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * set le temps d'exec
     * @param float $v temps
     */
    public function setExecutionTime($v)
    {
        $this->executionTime = $v;
    }

    /**
     * retourne le temps d'exec
     * @return float $v temps
     */
    public function getExecutionTime()
    {
        return $this->executionTime;
    }

    /**
     * alias de getExecutionTime pour le bundle statsd
     * retourne des millisecondes
     *
     * @return float
     */
    public function getTiming()
    {
        return $this->getExecutionTime() * 1000;
    }
}
