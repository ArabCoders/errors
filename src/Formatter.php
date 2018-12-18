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

use arabcoders\errors\Interfaces\ErrorInterface;
use arabcoders\errors\Interfaces\ErrorMapInterface;
use arabcoders\errors\Interfaces\FormatterInterface;

/**
 * Class Formatter
 *
 * @package arabcoders\errors
 */
class Formatter implements FormatterInterface
{
    /**
     * @var string Format error.
     */
    public const FORMAT_ERROR = '(%s) in (%s:%d) with message (%s) URI: (%s:%s)';

    /**
     * @var string Format exception.
     */
    public const FORMAT_EXCEPTION = '(%s) thrown in (%s:%d) %s URI: (%s:%s)';

    /**
     * Format the Error and return it as string.
     *
     * @param ErrorMapInterface $error Error instance.
     *
     * @return string
     */
    public function formatError( ErrorMapInterface $error ) : string
    {
        return sprintf(
            self::FORMAT_ERROR,
            ErrorInterface::ERROR_CODES[$error->getNumber()] ?? $error->getNumber(),
            $error->getFile(),
            $error->getLine(),
            $error->getMessage(),
            $_SERVER['REQUEST_METHOD'] ?? '',
            $_SERVER['REQUEST_URI'] ?? $_SERVER['PHP_SELF'] ?? ''
        );
    }

    /**
     * Format the exception and return it as string.
     *
     * @param \Throwable $exception The thrown exception.
     *
     * @return string
     */
    public function formatException( \Throwable $exception ) : string
    {
        return sprintf(
            self::FORMAT_EXCEPTION,
            get_class( $exception ),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getMessage() ? sprintf( 'with message (%s)', $exception->getMessage() ) : '',
            $_SERVER['REQUEST_METHOD'] ?? '',
            $_SERVER['REQUEST_URI'] ?? $_SERVER['PHP_SELF'] ?? ''
        );
    }
}