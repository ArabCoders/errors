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
     * @var int
     */
    private $type;

    /**
     * @var int|string
     */
    private $parameter;

    /**
     * @var bool
     */
    private $logging;

    /**
     * @var bool
     */
    private $displaying;

    /**
     * @var bool
     */
    private $exiting;

    /**
     * @var \Closure
     */
    private $closure;

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

    public function isOfType( int $type ) : bool
    {
        return $this->getType() === $type;
    }

    public function getType() : int
    {
        return $this->type;
    }

    public function getParameter()
    {
        return $this->parameter;
    }

    public function allowLogging() : bool
    {
        return $this->logging;
    }

    public function allowDisplaying() : bool
    {
        return $this->displaying;
    }

    public function allowExiting() : bool
    {
        return $this->exiting;
    }

    public function hasClosure() : bool
    {
        return ( $this->closure instanceof \Closure );
    }

    public function getClosure() : \Closure
    {
        if ( empty( $this->closure ) )
        {
            throw new \RuntimeException( 'Closure Is not defined.' );
        }

        return $this->closure;
    }
}