<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 25.04.2022
 * Time: 10:38
 */

namespace App\Abstracts;

use App\Redirect;

abstract class Rule
{
    /**
     * @var Redirect
     */
    private $redirect;
    /**
     * @var bool
     */
    private $make_redirect = false;
    //allow redirects
    /**
     * @var string
     */
    private $from;
    /**
     * @var string
     */
    private $to;

    /**
     * @var int
     */
    private $code = 301;


    public function __construct(Redirect $redirect)
    {
        $this->redirect = $redirect;
    }

    public function redirect()
    {
        return $this->redirect;
    }

    public function setTo(string $to, int $code = 301, $addArg = true)
    {
        $this->setRule();
        $this->redirect()->setRedirect($to, $this->from(), $code, $this->case(), $addArg);
        return $this;
    }

    public function setFrom(string $from)
    {
        $this->from = $from;
        return $this;
    }

    public function to()
    {
        return $this->to;
    }

    public function from()
    {
        return $this->from;
    }

    public function make_redirect()
    {
        return $this->make_redirect;
    }

    public function code()
    {
        return $this->code;
    }


    public function setRule()
    {
        $this->redirect()->setRedirectStopRule(get_class($this));
    }

    public function setRedirectAllowed(bool $value)
    {
        $this->setRule();
        $this->redirect()->setRedirectAllowed($value, $this->case());
        return $this;
    }

}
