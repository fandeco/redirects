<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 11.10.2022
 * Time: 15:40
 */

namespace App\Rules;

use App\Abstracts\Rule;

class Search extends Rule
{
    public function case(): string
    {
        return 'В базе найден редирект';
    }

    public function process(): bool
    {
        $url = $this->redirect()->request()->get();
        $result = $this->redirect()->searchRedirectUrl($url);
        if ($result !== false) {
            $this->setTo($result['target'], $result['code']);
            return true;
        }
        return false;
    }
}
