<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 11.10.2022
 * Time: 17:56
 */

namespace App\Rules;


use App\Abstracts\Rule;
use App\Implement\IRule;

class Slash extends Rule implements IRule
{

    public function case(): string
    {
        return 'Редирект слешев';
    }

    public function process(): bool
    {
        $uri = $this->redirect()->request()->get();
        if (substr($uri, -1) != '/') {
            $this->setTo($uri . '/');
        }
        return false;
    }
}
