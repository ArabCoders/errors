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
 * Interface FormatterInterface
 *
 * @package arabcoders\errors\Interfaces
 */
Interface FormatterInterface
{
    /**
     * Format the Error and return it as string.
     *
     * @param ErrorMapInterface $error Error instance.
     *
     * @return string
     */
    public function formatError( ErrorMapInterface $error ) : string;

    /**
     * Format the exception and return it as string.
     *
     * @param \Throwable $exception The thrown exception.
     *
     * @return string
     */
    public function formatException( \Throwable $exception ) : string;
}