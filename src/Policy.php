<?php
/**
 * This file is part of ( \arabcoders\errors ) project.
 *
 * (c) 2017 ArabCoders Ltd.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace arabcoders\errors;

use arabcoders\errors\Interfaces\PolicyInterface;

/**
 * Class Policy
 *
 * @package arabcoders\errors
 */
class Policy implements PolicyInterface
{
    /**
     * @var int Policy for type.
     */
    protected $type;

    /**
     * @var int|string Trigger parameter (Class FQN or Error code number).
     */
    protected $parameter;

    /**
     * @var bool Whether to allow logging of this error
     */
    protected $logging;

    /**
     * @var bool Whether to allow displaying of the error
     */
    protected $displaying;

    /**
     * @var bool Whether to halt the execution of the app.
     */
    protected $exiting;

    /**
     * @var \Closure Closure to call upon when encountering this error.
     */
    protected $closure;

    /**
     * PolicyInterface constructor.
     *
     * @param int        $type       {@see ErrorInterface::TYPE_ERROR} or {@see ErrorInterface::TYPE_EXCEPTION}
     * @param string|int $parameter  Class FQN or error code number.
     * @param bool       $logging    Enable logging of this error
     * @param bool       $displaying Enable the displaying of the error.
     * @param bool       $exiting    Halt the execution of the app when this error is encountered.
     * @param \Closure   $closure    Closure to call upon when encountering this error.
     *
     * @throws \InvalidArgumentException if {@see $parameter} is neither string nor int.
     */
    public function __construct( int $type, $parameter, bool $logging, bool $displaying, bool $exiting, \Closure $closure = null )
    {
        if ( !is_string( $parameter ) && !is_int( $parameter ) )
        {
            throw new \InvalidArgumentException( 'Parameter Type is not string or int.' );
        }

        $this->type       = $type;
        $this->parameter  = $parameter;
        $this->logging    = $logging;
        $this->displaying = $displaying;
        $this->exiting    = $exiting;
        $this->closure    = $closure;
    }

    /**
     * Is of type.
     *
     * @param int $type {@see ErrorInterface::TYPE_ERROR} or {@see ErrorInterface::TYPE_EXCEPTION}
     *
     * @return bool
     */
    public function isOfType( int $type ) : bool
    {
        return $this->getType() === $type;
    }

    /**
     * Get type.
     *
     * @return int
     */
    public function getType() : int
    {
        return $this->type;
    }

    /**
     * Get parameter.
     *
     * @return int|string
     */
    public function getParameter()
    {
        return $this->parameter;
    }

    /**
     * Do we allow the logging of this error.
     *
     * @return bool
     */
    public function allowLogging() : bool
    {
        return $this->logging;
    }

    /**
     * Do we allow the displaying of this error.
     *
     * @return bool
     */
    public function allowDisplaying() : bool
    {
        return $this->displaying;
    }

    /**
     * Do we allow the exiting of the application if we encounter this error.
     *
     * @return bool
     */
    public function allowExiting() : bool
    {
        return $this->exiting;
    }

    /**
     * Does this policy have closure.
     *
     * @return bool
     */
    public function hasClosure() : bool
    {
        return ( $this->closure instanceof \Closure );
    }

    /**
     * Get closure.
     *
     * @return \Closure
     */
    public function getClosure() : \Closure
    {
        if ( empty( $this->closure ) )
        {
            throw new \RuntimeException( 'Closure Is not defined.' );
        }

        return $this->closure;
    }
}