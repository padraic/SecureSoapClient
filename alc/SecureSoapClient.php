<?php
/**
 * Copyright (C) 2012 EasyWebstore Limited
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
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * @copyright Lee Conlin
 * @license MIT
 * @author Lee Conlin <leeconlin@easywebstore.net>
 */

namespace alc;

/**
 * A SoapClient extension that implements secure-by-default methodology
 *
 * @author Lee Conlin <leeconlin@easywebstore.net>
 * @package EasyWebstore
 */
class SecureSoapClient extends \SoapClient {

    private $cache_dir = '/home/example/htdocs/cache/';

    /**
     * Constructor
     *
     * @param string $wsdl URL to the WSDL of the SOAP web service.
     * @param string $cache_path Directory path relative to document root where to store wsdl file. Must be writeable.
     * @param array  $options Array of options.
     * @See http://php.net/manual/en/soapclient.soapclient.php SoapClient constructor documentation.
     * @throws Exception
     */
    public function __construct($wsdl, $cache_path = '/wsdl_cache/', $options = array())
    {
        $this->cache_dir = __DIR__ . $cache_path;

        if (!is_dir($this->cache_dir)) {
            // Attempt to create it
            if (!mkdir($this->cache_dir, 0777, true)) {
                throw new Exception("Directory specified by \$cache_path ($this->cache_dir}) does not exist and could not be created.");
            }
        }

        $file = md5(uniqid()).'.xml';

        if (($fp = fopen($this->cache_dir.$file, "w")) == false) {
            throw new Exception('Could not create local WDSL file ('.$this->cache_dir.$file.')');
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $wsdl);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        if (($xml = curl_exec($ch)) === false) {
            //curl_close($ch);
            fclose($fp);
            unlink($this->cache_dir.$file);

            throw new Exception(curl_error($ch));
        }

        curl_close($ch);
        fclose($fp);
        $wsdl = $this->cache_dir.$file;

        parent::__construct($wsdl, $options);

        unlink($this->cache_dir.$file);
    }

    /**
    * Call a url using curl
    *
    * @param string $url
    * @param string $data
    * @param string $action
    * @return string
    * @throws SoapFault on curl connection error
    */
    protected function callCurl($url, $data,$action) {

        $headers = array(
            'Content-Type: text/xml; charset=utf-8',
            'Accept: text/xml',
            'SOAPAction: "' . $action . '"',
            'Content-Length: '.strlen($data)
        );

        $handle   = curl_init();
        curl_setopt($handle, CURLOPT_HEADER, false);
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_FAILONERROR, false);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers );
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($handle);

        if (empty($response)) {
            throw new \SoapFault(curl_errno($handle)==null?'':curl_errno($handle),'CURL error: '.curl_error($handle));
        }

        curl_close($handle);
        return $response;
    }

    /**
     * Overrides SoapClient::__doRequest
     *
     * This method injects the StoreID and ApiKey into every request
     * automatically.
     *
     * @param string $request
     * @param string $location
     * @param string $action
     * @param string $version
     * @param int    $one_way
     * @return string
     */
    public function __doRequest($request,$location,$action,$version,$one_way = 0) {

        preg_match("/[^\/]+$/", $action, $matches);
        $short_action = $matches[0];

        return $this->callCurl($location,$request,$action);
    }

    /**
     * Overrides SoapClient::__call
     *
     * @param string $function_name
     * @param array  $arguments
     * @return mixed
     */
    public function __call($function_name, $arguments)
    {
        return parent::__call($function_name, $arguments);
    }

    /**
     * Overrides SoapClient::__soapCall
     *
     * @param string $function_name
     * @param array  $arguments
     * @param array  $options
     * @param string $input_headers
     * @param array  $output_headers
     * @return mixed
     */
    public function __soapCall ($function_name, $arguments, $options = null, $input_headers = null, &$output_headers = null)
    {
        return parent::__soapCall($function_name, $arguments, $options, $input_headers, $output_headers);
    }
}
