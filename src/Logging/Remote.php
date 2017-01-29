<?php
/**
 * This file is part of ( framework ) project.
 *
 * (c) 2017 ArabCoders Ltd.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace arabcoders\errors\Logging;

use GuzzleHttp\ClientInterface as RequestInterface,
    GuzzleHttp\Promise\PromiseInterface,
    arabcoders\errors\Logging\Interfaces\LoggingInterface,
    arabcoders\errors\Interfaces\MapInterface;

/**
 * Class Remote
 *
 * @package arabcoders\errors\Logging
 */
class Remote implements LoggingInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var PromiseInterface[]
     */
    private $async = [];

    /**
     * @var string
     */
    private $server;

    /**
     * @var MapInterface
     */
    private $map;

    /**
     * Remote constructor.
     *
     * @param RequestInterface $client
     * @param string           $url
     */
    public function __construct( RequestInterface $client, string $url )
    {
        $this->request = $client;
        $this->server  = $url;
    }

    public function process() : LoggingInterface
    {
        $this->async[] = $this->request->requestAsync( 'post', $this->server, [
            'form_params' => [
                'message'    => $this->getMap()->getMessage(),
                'trace'      => json_encode( $this->getMap()->getTrace() ),
                'structured' => json_encode( $this->getMap()->getStructured() ),
            ],
        ] );

        return $this;
    }

    public function clear() : LoggingInterface
    {
        $this->map = null;

        return $this;
    }

    public function __destruct()
    {
        foreach ( $this->async as $guzzle )
        {
            if ( ( $guzzle instanceof PromiseInterface ) )
            {
                $guzzle->wait();
            }
        }

        $this->request = null;
    }

    public function setMap( MapInterface $map ) : LoggingInterface
    {
        $this->map = $map;

        return $this;
    }

    public function getMap() : MapInterface
    {
        return $this->map;
    }
}