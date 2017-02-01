<?php
/**
 * This file is part of ( framework ) project.
 *
 * (c) 2017 ArabCoders Ltd.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace arabcoders\errors;

use arabcoders\errors\
{
    Interfaces\ErrorInterface, Interfaces\ErrorMapInterface, Interfaces\FormatterInterface
};

class Formatter implements FormatterInterface
{
    CONST FORMAT_ERROR = "(%s) in (%s:%d) with message (%s) URI: (%s:%s)";

    CONST FORMAT_EXCEPTION = "(%s) thrown in (%s:%d) %s URI: (%s:%s)";

    public function formatError( ErrorMapInterface $map ) : string
    {
        return sprintf( self::FORMAT_ERROR,
                        ErrorInterface::ERROR_CODES[$map->getNumber()] ?? $map->getNumber(),
                        $map->getFile(),
                        $map->getLine(),
                        $map->getMessage(),
                        $_SERVER['REQUEST_METHOD'] ?? '',
                        $_SERVER['REQUEST_URI'] ?? $_SERVER['PHP_SELF']  ?? ''
        );
    }

    public function formatException( \Throwable $e ) : string
    {
        return sprintf( self::FORMAT_EXCEPTION,
                        get_class( $e ),
                        $e->getFile(),
                        $e->getLine(),
                        $e->getMessage() ? sprintf( 'with message (%s)', $e->getMessage() ) : '',
                        $_SERVER['REQUEST_METHOD'] ?? '',
                        $_SERVER['REQUEST_URI'] ?? $_SERVER['PHP_SELF'] ?? ''
        );
    }
}