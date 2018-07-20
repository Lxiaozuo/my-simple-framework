<?php
/**
 * Created by PhpStorm.
 * User: summer.zuo
 * Date: 2018/7/19
 * Time: 17:09
 */

namespace sf\web;

use Couchbase\Exception;
use sf\Sf;

class Request extends \sf\base\Request
{

    private $_method;
    private $_methodParam = '_method';
    private $_queryParams;


    /**
     * 解析用户请求
     * 请求路由，请求参数
     * 跳转到对应的请求
     */
    public function resolve()
    {
        // 判断请求方法
        if ($this->getMethod() === 'GET') {
            $params = $this->getQueryParams();
        } else {
            $params = $this->getBodyParams();
        }

        $route = $this->getPathInfo();

        // 根据路由跳转到对应的控制器
        return [$route, $params];
    }

    /**
     * 获取请求方法
     */
    public function getMethod()
    {
        if (isset($_POST[$this->_methodParam])) {
            return strtoupper($_POST[$this->_methodParam]);
        }
        if ($this->headers === null) {
            $this->headers = $this->getHeaders();
        }
        if ($this->headers->has('X-Http-Method-Override')) {
            return strtoupper($this->headers->get('X-Http-Method-Override'));
        }

        if (isset($_SERVER['REQUEST_METHOD'])) {
            return strtoupper($_SERVER['REQUEST_METHOD']);
        }

        return 'GET';
    }

    /**
     * 根据请求方法获取请求参数
     */
    public function getQueryParams()
    {
        // query_string
        if ($this->_queryParams === null) {
            return $_GET;
        }

        return $this->_queryParams;
    }

    private $_pathInfo;

    /**
     * 获取请求路径，去除掉入口文件
     */
    public function getPathInfo()
    {
        $requestUri = $this->getRequestUri();

        if (($pos = strpos($requestUri, '?')) !== false) {
            $requestUri = substr($requestUri, 0, $pos);
        }

        $pathInfo = urldecode($requestUri);
        if (!preg_match('%^(?:
            [\x09\x0A\x0D\x20-\x7E]              # ASCII
            | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
            | \xE0[\xA0-\xBF][\x80-\xBF]         # excluding overlongs
            | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
            | \xED[\x80-\x9F][\x80-\xBF]         # excluding surrogates
            | \xF0[\x90-\xBF][\x80-\xBF]{2}      # planes 1-3
            | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
            | \xF4[\x80-\x8F][\x80-\xBF]{2}      # plane 16
            )*$%xs', $pathInfo)
        ) {
            $pathInfo = utf8_encode($pathInfo);
        }
        $scriptUrl = $this->getScriptUrl();
        if (strpos($pathInfo, $scriptUrl) === 0) {
            $pathInfo = substr($pathInfo, strlen($scriptUrl));
        }

        if (substr($pathInfo, 0, 1) === '/') {
            $pathInfo = substr($pathInfo, 1);
        }

        return $pathInfo;
    }

    private $_scriptUrl;

    public function getScriptUrl()
    {
        if ($this->_scriptUrl === null) {
            $scriptFile = isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : '';
            $scriptName = basename($scriptFile);
            if (isset($_SERVER['SCRIPT_NAME']) && basename($_SERVER['SCRIPT_NAME']) === $scriptName) {
                $this->_scriptUrl = $_SERVER['SCRIPT_NAME'];
            } else {
                throw new Exception('Unable to determine the entry script URL.');
            }
        }

        return $this->_scriptUrl;
    }

    public function getRequestUri()
    {
        if ($this->headers->has('X-Rewrite-Url')) { // IIS
            $requestUri = $this->headers->get('X-Rewrite-Url');
        } elseif (isset($_SERVER['REQUEST_URI'])) {
            $requestUri = $_SERVER['REQUEST_URI'];
        } elseif (isset($_SERVER['ORIG_PATH_INFO'])) {  // IIS 5.0 CGI
            $requestUri = $_SERVER['ORIG_PATH_INFO'];

            if (!empty($requestUri)) {
                $requestUri .= '?' . $_SERVER['QUERY_STRING'];
            }
        } else {
            throw new \Exception('Unable to determine the request UIR.');
        }

        return $requestUri;
    }

    public $parsers = [
        'application/json' => 'sf\web\JsonParse'
    ];

    private $_bodyParams;

    /**
     * 获取post参数
     * If no parsers are configured for the current [[contentType]] it uses the PHP function `mb_parse_str()`
     * to parse the [[rawBody|request body]].
     * @return mixed
     */
    public function getBodyParams()
    {
        if ($this->_bodyParams === null) {

            // 如果用户在post参数中自定义了_method，就取post的值，需要unset掉_method参数
            if (isset($_POST[$this->_methodParam])) {
                $this->_bodyParams = $_POST;
                unset($this->_bodyParams[$this->_methodParam]);
                return $this->_bodyParams;
            }

            // 2.根据用户传入的contentType进行参数解析
            $rawContentType = $this->getContentType();
            // e.g. text/html; charset=UTF-8
            if ($pos = strpos($rawContentType, ';') !== false) {
                $contentType = substr($rawContentType, 0, $pos);
            } else {
                $contentType = $rawContentType;
            }

            if (isset($this->parsers[$contentType])) {
                $parser = sf::createObject($this->parsers[$contentType]);
                $this->_bodyParams = $parser->parseRequest($this->getRawBody());
            } elseif (isset($this->parsers['*'])) {
                $parser = sf::createObject($this->parsers['*']);
                $this->_bodyParams = $parser->parseRequest($this->getRawBody());
            } elseif ($this->getMethod() === 'POST') {
                $this->_bodyParams = $_POST;
            } else {
                $this->_bodyParams = [];
                mb_parse_str($this->getRawBody(), $this->_bodyParams);
            }
        }

        return $this->_bodyParams;
    }

    public function getContentType()
    {
        if (isset($_SERVER['CONTENT_TYPE'])) {
            return $_SERVER['CONTENT_TYPE'];
        }

        $header = $this->getHeaders();
        if (isset($header['Content-Type'])) {
            return $header['Content-Type'];
        }

    }

    private $_rawBody;

    public function getRawBody()
    {
        if ($this->_rawBody === null) {
            $this->_rawBody = file_get_contents('php://input');
        }
        return $this->_rawBody;
    }

    public $headers;

    public function getHeaders()
    {
        if ($this->headers === null) {
            $this->headers = sf::createObject('sf\web\Header');

            if (function_exists('getallheaders')) {
                $headers = getallheaders();
                foreach ($headers as $name => $value) {
                    $this->headers->add($name, $value);
                }

            } elseif (function_exists('http_get_request_headers')) {
                $headers = http_get_request_headers();
                foreach ($headers as $name => $value) {
                    $this->headers->add($name, $value);
                }

            } else {
                foreach ($_SERVER as $name => $value) {
                    if (strncmp($name, 'HTTP_', 5) === 0) {
                        // eg. HTTP_ACCEPT_LANGUAGE => Accept-Language
                        $name = str_replace(' ', '-', ucwords(str_replace('_', ' ', substr($name, 5))));
                        $this->headers->add($name, $value);
                    }
                }
            }
        }

        return $this->headers;
    }


    public function get($name, $defaultValue = null)
    {

    }

    public function post($name, $defaultValue = null)
    {

    }
}
