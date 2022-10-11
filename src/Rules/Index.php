<?php
/**
 * Правило применяется для главной странице чтобы сделать редирект на главную в случае множества слэшев
 */

namespace App\Rules;


use App\Abstracts\Rule;
use App\Implement\IRule;

class Index extends Rule implements IRule
{

    public function case(): string
    {
        return 'Редирект для главной страницы';
    }

    public function process(): bool
    {
        $url = $this->redirect()->request()->full();
        if (strripos($url, '?') !== false) {
            $data = explode('?', $url);
            $uri = $data[0];
        } else {
            $uri = $url;
        }
        $redirect = false;


        switch ($uri) {
            case  '/index':
            case  '/index.php':
            case  '/index.php/':
            case  '/index/':
                $redirect = true;
                break;
            default:
                if ($uri !== '/') {
                    // Заменяем все символы / чтобы определить что мы находимся на главной странице
                    $main = str_replace('/', '', $uri);
                    // Если это главгная страница
                    if ($main === '') {
                        $this->setTo(''); // Редирект на главную страницу
                    }
                }
                break;
        }


        if ($redirect) {
            $this->setTo(''); // Редирект на главную страницу;
        }

        return false;
    }
}
