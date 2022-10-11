<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 11.10.2022
 * Time: 15:25
 */

namespace App;


use App\Exceptions\ExceptionRule;

class Request
{
    /**
     * @var string
     */
    private $url;

    /* @var null|array */
    protected $args;
    /**
     * @var string
     */
    private $first;

    /**
     * @param string $url
     */
    public function __construct(string $url, $args = null)
    {
        if (strripos($url, '?') !== false) {
            $data = explode('?', $url);
            throw new ExceptionRule('В url запрещено передавать аргументы в виде гет параметров:' . $data[1] . ' аргементы передаются следующей переменной в виде массива');
        }

        $this->url = $url;
        $this->first = $url;
        if ($args) {
            if (!is_array($args)) {
                throw new ExceptionRule('Агременты должны быть в виде массива');
            }
            $this->args = $args;
        }
    }

    public function setRequestUri(string $uri)
    {
        $this->request_uri = $uri;
        return $this;
    }

    public function requestUri()
    {
        return $this->request_uri;
    }

    public function get()
    {
        return $this->url;
    }

    public function full()
    {
        return $this->addArgs($this->url);
    }

    public function addArgs($url)
    {
        if ($this->args) {
            $args = $this->toQueryString($this->args);
            $url .= '?' . $args;
        }
        return $url;
    }

    public function first()
    {
        return $this->addArgs($this->first);
    }

    public function args()
    {
        if ($this->args) {
            $args = $this->toQueryString($this->args);
            return '?' . $args;
        }
        return null;
    }

    public function toQueryString(array $parameters = array(), $numPrefix = '', $argSeparator = '&')
    {
        return http_build_query($parameters, $numPrefix, $argSeparator);
    }

    public function update(string $to)
    {
        $this->url = $to;
    }

}
