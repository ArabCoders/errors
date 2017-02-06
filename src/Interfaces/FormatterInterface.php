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
 * Interface FormatterInterface
 *
 * @package arabcoders\errors\Interfaces
 */
Interface FormatterInterface
{
    public function formatError( ErrorMapInterface $map ) : string;

    public function formatException( \Throwable $e ) : string;
}