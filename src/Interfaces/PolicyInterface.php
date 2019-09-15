<?php
/**
 * This file is part of ( @package \arabcoders\errors ) project.
 *
 * (c) 2017 ArabCoders Ltd.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace arabcoders\errors\Interfaces;

/**
 * Interface PolicyInterface
 *
 * @package arabcoders\errors\Interfaces
 */
interface PolicyInterface
{
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
    public function __construct( int $type, $parameter, bool $logging, bool $displaying, bool $exiting, \Closure $closure = null );

    /**
     * Is of type.
     *
     * @param int $type {@see ErrorInterface::TYPE_ERROR} or {@see ErrorInterface::TYPE_EXCEPTION}
     *
     * @return bool
     */
    public function isOfType( int $type ) : bool;

    /**
     * Get type.
     *
     * @return int
     */
    public function getType() : int;

    /**
     * Get parameter.
     *
     * @return int|string
     */
    public function getParameter();

    /**
     * Do we allow the logging of this error.
     *
     * @return bool
     */
    public function allowLogging() : bool;

    /**
     * Do we allow the displaying of this error.
     *
     * @return bool
     */
    public function allowDisplaying() : bool;

    /**
     * Do we allow the exiting of the application if we encounter this error.
     *
     * @return bool
     */
    public function allowExiting() : bool;

    /**
     * Does this policy have closure.
     *
     * @return bool
     */
    public function hasClosure() : bool;

    /**
     * Get closure.
     *
     * @return \Closure
     */
    public function getClosure() : \Closure;
}