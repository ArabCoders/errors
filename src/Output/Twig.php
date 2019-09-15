<?php
/**
 * This file is part of ( @package \arabcoders\errors ) project.
 *
 * (c) 2017 ArabCoders Ltd.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace arabcoders\errors\Output;

use arabcoders\errors\Interfaces\ErrorInterface;
use arabcoders\errors\Interfaces\MapInterface;
use arabcoders\errors\Output\Interfaces\OutputInterface;
use Twig_Environment;

/**
 * Class Twig
 *
 * @package arabcoders\errors\Output
 */
class Twig implements OutputInterface
{

    /**
     * @var MapInterface Map class.
     */
    private $map;

    /**
     * @var Twig_Environment Initialized twig environment.
     */
    private $twig;

    /**
     * @var string twig template name. eg (errorHandler.twig)
     */
    private $template;

    /**
     * Twig constructor
     *
     * @param Twig_Environment $twig     Initialized twig environment.
     * @param string           $template twig template name. eg (errorHandler.twig)
     */
    public function __construct( Twig_Environment $twig, string $template )
    {
        $this->twig = $twig;

        $this->template = $template;
    }

    /**
     * it will provide {@see Twig_Environment} with 5 variables named as the following.
     * ```php
     * [
     *      'type'       => 'error kind',
     *      'className'  => 'Exception name or null',
     *      'message'    => 'the error message as string'
     *      'trace'      => 'trace data as json encoded string or null if empty',
     *      'structured' => 'structured data as json encoded string or null if empty',
     * ]
     * ```
     *
     * @return OutputInterface
     * @throws \ReflectionException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function display() : OutputInterface
    {
        $trace = $this->getMap()->getTrace();

        $structured = $this->getMap()->getStructured();

        if ( ErrorInterface::TYPE_EXCEPTION === $this->getMap()->getType() )
        {
            $class = get_class( $this->getMap()->getException() );
            $type  = ( new \ReflectionClass( $class ) )->getShortName();
        }

        print $this->twig->render( $this->template, [
            'className'  => $type ?? null,
            'type'       => $this->getMap()->getType(),
            'message'    => $this->getMap()->getMessage(),
            'UNIQUE_ID'  => $this->getMap()->getId(),
            'trace'      => !empty( $trace ) ? json_encode( $trace, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) : null,
            'structured' => !empty( $structured ) ? json_encode( $structured, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) : null,
        ] );

        return $this;
    }

    /**
     * Set map.
     *
     * @param MapInterface $map Map class.
     *
     * @return OutputInterface
     */
    public function setMap( MapInterface $map ) : OutputInterface
    {
        $this->map = $map;

        return $this;
    }

    /**
     * Get map.
     *
     * @return MapInterface
     */
    public function getMap() : MapInterface
    {
        return $this->map;
    }
}