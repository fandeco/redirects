<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 11.10.2022
 * Time: 15:47
 */

namespace App;

use App\Abstracts\Rule;
use App\Exceptions\ExceptionRule;

class Redirect
{
    /**
     * @var array
     */
    private $result;
    /**
     * @var string
     */
    private $url;

    protected $from;
    protected $to;
    protected $code;
    protected $case;


    /**
     * @var Urls
     */
    private $urls;
    /**
     * @var bool
     */
    private $redirectAllowed = true;
    private $redirectFound = false;
    private $redirectRule;
    private $chain_redirects = [];
    /**
     * @var mixed
     */
    private $rule;

    public function __construct(Site $site, Request $request)
    {
        $this->site = $site;
        $this->request = $request;
    }

    public function site()
    {
        return $this->site;
    }

    public function request()
    {
        return $this->request;
    }

    public function addRule(string $rule)
    {
        $this->rules[] = $rule;
        return $this;
    }

    public function getRules()
    {
        return $this->rules;
    }

    public function process()
    {
        $rules = $this->getRules();
        $check = true;

        foreach ($rules as $rule) {
            /* @var Rule $Handler */
            $this->rule = (new $rule($this));
            $this->rule->setFrom($this->request()->get());
            if ($check) {
                $this->rule->process();
            }
            $this->result[$rule] = $check;
        }
        return $this;
    }


    public function setRedirect($to, $from, $code, $case)
    {
        $this->redirectFound = true;
        $this->request()->update($to);
        $this->to = $to;
        $this->from = $from;
        $this->code = $code;
        $this->case = $case;
        $this->setChain($this->to);
        #$this->setChain($this->to());
        return $this;
    }

    public function setChain(string $to)
    {
        $this->chain_redirects[get_class($this->rule)] = $to;
        return $this;
    }

    public function chain()
    {
        return $this->chain_redirects;
    }

    public function to()
    {
        $to = $this->to;
        if (!is_null($to)) {
            if ($args = $this->request()->args()) {
                $to .= $args;
            }
            // Добавление домена только если ссылка не является мультидоменной
            if (!$this->isDomain($to) && $site = $this->site()) {
                $to = $site->getUrl($to);
            }
        }
        return $to;
    }


    public function from()
    {
        $from = $this->from;
        if (!is_null($from)) {
            if ($args = $this->request()->args()) {
                $from .= $args;
            }
            if ($site = $this->site()) {
                $from = $site->getUrl($from);
            }
        }
        return $from;
    }

    public function first()
    {
        $first = $this->request()->first();
        if ($site = $this->site()) {
            $first = $site->getUrl($first);
        }
        return $first;
    }

    public function code()
    {
        return $this->code;
    }

    public function responseCode()
    {
        $responseCode = null;
        $code = $this->code();
        if ($code) {
            switch ($this->code()) {
                case 301:
                    $responseCode = 'HTTP/1.1 301 Moved Permanently';
                    break;
                default:
                    throw new ExceptionRule('Не известный код для редиректа ' . $this->code());
            }
        }
        return $responseCode;
    }


    public function case()
    {
        return $this->case;
    }

    public function go()
    {
        if ($this->redirectAllowed() && $this->redirectFound()) {
            return $this->to();
        }
        return null;
    }

    public function isDomain(string $url)
    {
        if (strripos($url, 'http') !== false || strripos($url, 'https') !== false) {
            return true;
        }
        return false;
    }

    public function toArray()
    {
        return [
            'go' => $this->go(),
            'redirect_allowed' => $this->redirectAllowed(),
            'redirect_found' => $this->redirectFound(),
            'redirect_rule' => $this->redirectRule(),
            'case' => $this->case(),
            'from' => $this->first(),
            'to' => $this->to(),
            'code' => $this->code(),
            'responseCode' => $this->responseCode(),
            'chain' => $this->chain(),
            'rules' => $this->result,
        ];
    }

    public function setUrls(Urls $Urls)
    {
        $this->urls = $Urls;
        return $this;
    }

    public function searchRedirectUrl(string $url)
    {
        if (is_null($this->urls)) {
            return false;
        }
        return $this->urls->get($url);
    }

    public function redirectAllowed()
    {
        return $this->redirectAllowed;
    }

    public function setRedirectAllowed(bool $value, string $case)
    {
        $this->case = $case;
        $this->redirectAllowed = $value;
        return $this;
    }

    public function setRedirectStopRule(string $rule)
    {
        $this->redirectRule = $rule;
        return $this;
    }

    public function redirectRule()
    {
        return $this->redirectRule;
    }

    public function redirectFound()
    {
        return $this->redirectFound;
    }
}
