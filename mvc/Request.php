<?php

/**
 * The MIT License
 *
 * Copyright 2018 Gregory V Lominoga aka Gromodar <@gromodar at telegram>, Symedia Ltd.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Project;

/**
 * @category   
 * @package    Request
 * @author Gregory V Lominoga aka Gromodar <@gromodar at telegram>, Symedia Ltd
 */
class Request
{
   
    /**
     * @var array
     */
    protected $params;


    /**
     * $_SERVER
     * @var array
     */
    protected $server;
    
    /**
     * $_POST
     * @var array
     */
    protected $post;
    
    /**
     *
     * @var null|bullean
     */
    protected $isPost;
    
    /**
     * @var string
     */
    protected $requestUri;
   
    function __construct()
    {
        $server = filter_input_array(INPUT_SERVER);
        if ($server) {
            $this->server = $server;
            $this->requestUri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);
        }
        $post = filter_input_array(INPUT_POST);
        if ($post) {
            $this->isPost = true;
            $this->post = $post;
        }
    }
    
    public function getRequestUri()
    {
        return $this->requestUri;
    }
    
    
    public function getParams($name = null)
    {
        if (null === $name) {
            return $this->params;
        }
        return isset($this->params[$name]) ? $this->params[$name] : null;
    }
    
    public function setParam($name, $value = null)
    {
        $this->params[$name] = $value;
        return $this;
    }
    
    public function isPost()
    {
        return $this->isPost;
    }
    
    public function post($name = null)
    {
        if (null === $name) {
            return $this->post;
        }
        return isset($this->post[$name]) ? $this->post[$name] : null;
    }
    
    public function getHost()
    {
        return filter_var($this->server['HTTP_HOST'], FILTER_SANITIZE_URL);
    }
}
