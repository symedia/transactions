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

/**
 *
 * 
 * @category   
 * @package    Request
 * @author Gregory V Lominoga aka Gromodar <@gromodar at telegram>, Symedia Ltd
 */
class Request
{
    /**
     * $_SERVER
     * @var array
     */
    protected $server;
    
    /**
     * $_REQUEST
     * @var array
     */
    protected $request;
    
    /**
     * $_POST
     * @var array
     */
    protected $post;
    
    /**
     * $_GET
     * @var array
     */
    protected $get;
    
    /**
     *
     * @var null|bullean
     */
    protected $isGet;
    
    /**
     *
     * @var null|bullean
     */
    protected $isPost;
   
    /**
     *
     * @var string
     */
    protected $uri;
            
    function __construct()
    {
        $server = filter_input_array(INPUT_SERVER);
        if ($server) {
            $this->server = $server;
            $this->host = filter_input(INPUT_SERVER, 'HTTP_HOST');
            $this->protocol = filter_input(INPUT_SERVER, 'HTTPS');
            $this->uri = $this->protocol . $this->host;
        }
        
        $request = filter_input_array(INPUT_REQUEST);
        if ($request) {
            $this->request = $request;
        }
        
        $post = filter_input_array(INPUT_POST);
        if ($post) {
            $this->isPost = true;
            $this->post = $post;
        }
        
        $get = filter_input_array(INPUT_GET);
        if ($get) {
            $this->isGet = true;
            $this->get = $get;
            $this->uri .= $this->uri ? $this->get : null ;
        }
    }
    
    
}
