<?php
/**
 * This file is part of ( \arabcoders\errors ) project.
 *
 * (c) 2017 ArabCoders Ltd.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace arabcoders\errors\Output;

use arabcoders\errors\
{
    Interfaces\ErrorInterface, Interfaces\MapInterface, Output\Interfaces\OutputInterface
};
use Twig_Environment;

/**
 * Class Twig
 *
 * @package arabcoders\errors\Output
 */
class Twig implements OutputInterface
{

    /**
     * @var MapInterface
     */
    private $map;

    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var string
     */
    private $template;

    /**
     * Twig constructor,
     *
     * @param Twig_Environment $twig
     * @param string           $template
     */
    public function __construct( Twig_Environment $twig, string $template )
    {
        $this->twig = $twig;

        $this->template = $template;
    }

    /**
     * it will provide {@see Twig_Environment} with 5 variables named as the following.
     * <code>
     * [
     *      'type'       => 'error kind',
     *      'className'  => 'Exception name or null',
     *      'message'    => 'the error message as string'
     *      'trace'      => 'trace as json encoded string or null if empty',
     *      'structured' => 'trace as json encoded string or null if empty',
     * ]
     * </code>
     */
    public function display()
    {
        $trace = $this->getMap()->getTrace();

        $structured = $this->getMap()->getStructured();

        if ( $this->getMap()->getType() === ErrorInterface::TYPE_EXCEPTION )
        {
            $class = get_class( $this->getMap()->getException() );
            $type  = ( new \ReflectionClass( $class ) )->getShortName();
        }

        print $this->twig->render( $this->template, [
            'className'  => $type ?? null,
            'type'       => $this->getMap()->getType(),
            'message'    => $this->getMap()->getMessage(),
            'trace'      => ( !empty( $trace ) ) ? json_encode( $trace, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) : null,
            'structured' => ( !empty( $structured ) ) ? json_encode( $structured, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) : null,
        ] );
    }

    public function setMap( MapInterface $map ) : OutputInterface
    {
        $this->map = $map;

        return $this;
    }

    public function getMap() : MapInterface
    {
        return $this->map;
    }
}