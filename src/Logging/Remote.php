<?php
/**
 * This file is part of ( \arabcoders\errors ) project.
 *
 * (c) 2017 ArabCoders Ltd.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace arabcoders\errors\Logging;

use arabcoders\errors\Interfaces\MapInterface;
use arabcoders\errors\Logging\Interfaces\LoggingInterface;
use GuzzleHttp\ClientInterface as RequestInterface;
use GuzzleHttp\Promise\PromiseInterface;

/**
 * Class Remote
 *
 * @package arabcoders\errors\Logging
 */
class Remote implements LoggingInterface
{
    /**
     * @var RequestInterface Initialized Guzzle Compatible class.
     */
    private $request;

    /**
     * @var PromiseInterface[] Instances of active requests.
     */
    private $async = [];

    /**
     * @var string Server url to post data to.
     */
    private $server = '';

    /**
     * @var MapInterface Map class.
     */
    private $map;

    /**
     * Remote constructor.
     *
     * @param RequestInterface $client Initialized GuzzleHttp Compatible class.
     * @param string           $url    Server url to post data to.
     */
    public function __construct( RequestInterface $client, string $url )
    {
        $this->request = $client;
        $this->server  = $url;
    }

    /**
     * Process data to log.
     */
    public function process() : LoggingInterface
    {
        $this->async[] = $this->request->requestAsync( 'post', $this->server, [
            'form_params' => [
                'id'         => $this->getMap()->getId(),
                'message'    => $this->getMap()->getMessage(),
                'trace'      => json_encode( $this->getMap()->getTrace() ),
                'structured' => json_encode( $this->getMap()->getStructured() ),
            ],
        ] );

        return $this;
    }

    /**
     * Clear log data.
     *
     * @return LoggingInterface
     */
    public function clear() : LoggingInterface
    {
        $this->map = null;

        return $this;
    }

    /**
     * Free resources and wait on active requests.
     */
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

    /**
     * Set map.
     *
     * @param MapInterface $map Map class.
     *
     * @return LoggingInterface
     */
    public function setMap( MapInterface $map ) : LoggingInterface
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