<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 11.10.2022
 * Time: 15:40
 */

namespace App\Rules;

use App\Abstracts\Rule;
use App\Implement\IRule;

class Search extends Rule implements IRule
{

    public function case(): string
    {
        return 'Запуск через функцию';
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
