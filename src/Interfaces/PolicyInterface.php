<?php
/**
 * This file is part of ( \arabcoders\errors ) project.
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
     * @param string|int $parameter  Error Number or Exception FQN.
     * @param bool       $logging    whether to enable logging or not.
     * @param bool       $displaying whether to enable displaying if the error or not.
     * @param bool       $exiting    whether to shutdown after encountering this error.
     * @param \Closure   $closure    Closure to call upon encountering this error.
     */
    public function __construct( int $type, $parameter, bool $logging, bool $displaying, bool $exiting, \Closure $closure = null );

    /**
     * is of type.
     *
     * @param int $type {@see ErrorInterface::TYPE_ERROR} or {@see ErrorInterface::TYPE_EXCEPTION}
     *
     * @return bool
     */
    public function isOfType( int $type ) : bool;

    /**
     * Get Error Type.
     *
     * @return int
     */
    public function getType() : int;

    /**
     * Get Parameter as in Error Number or Exception Name.
     *
     * @return int|string
     */
    public function getParameter();


    /**
     * Allow The Logging of this error.
     *
     * @return bool
     */
    public function allowLogging() : bool;

    /**
     * Allow The Displaying of this error.
     *
     * @return bool
     */
    public function allowDisplaying() : bool;

    /**
     * Whether to shutdown the app after encountering this error.
     *
     * @return bool
     */
    public function allowExiting() : bool;

    /**
     * Does This Policy have closure?
     *
     * @return bool
     */
    public function hasClosure() : bool;

    /**
     * Get Closure.
     *
     * @return \Closure
     */
    public function getClosure() : \Closure;
}