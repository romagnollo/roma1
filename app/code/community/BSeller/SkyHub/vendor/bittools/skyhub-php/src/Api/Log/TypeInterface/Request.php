<?php
/**
 * B2W Digital - Companhia Digital
 *
 * Do not edit this file if you want to update this SDK for future new versions.
 * For support please contact the e-mail bellow:
 *
 * sdk@e-smart.com.br
 *
 * @category  SkuHub
 * @package   SkuHub
 *
 * @copyright Copyright (c) 2018 B2W Digital - BSeller Platform. (http://www.bseller.com.br).
 *
 * @author    Tiago Sampaio <tiago.sampaio@e-smart.com.br>
 */

namespace SkyHub\Api\Log\TypeInterface;

class Request extends TypeAbstract implements TypeRequestInterface
{
    
    /**
     * Request constructor.
     *
     * @param int          $requestId
     * @param string       $method
     * @param string       $uri
     * @param string|array $body
     * @param array        $headers
     * @param array        $requestOptions
     * @param string       $protocolVersion
     */
    public function __construct(
        $requestId,
        $method = null,
        $uri = null,
        $body = null,
        array $headers = [],
        array $requestOptions = [],
        $protocolVersion = null
    ) {
        parent::__construct($requestId, $body, $headers, $protocolVersion);
        
        $this->setMethod($method)
            ->setUri($uri)
            ->setOptions($requestOptions);
    }
    
    
    /**
     * @param string $method
     *
     * @return $this
     */
    public function setMethod($method = null)
    {
        $this->data['method'] = (string) $method;
        return $this;
    }
    
    
    /**
     * @param string $uri
     *
     * @return $this
     */
    public function setUri($uri = null)
    {
        $this->data['uri'] = (string) $uri;
        return $this;
    }
    
    
    /**
     * @param array $requestOptions
     *
     * @return $this
     */
    public function setOptions(array $requestOptions = [])
    {
        $this->data['options'] = (array) $requestOptions;
        return $this;
    }
}
