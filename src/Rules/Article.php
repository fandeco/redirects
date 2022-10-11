<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 11.10.2022
 * Time: 16:01
 */

namespace App\Rules;


use App\Abstracts\Rule;
use App\Implement\IRule;

class Article extends Rule implements IRule
{

    public function case(): string
    {
        return 'Редирект на артикул';
    }

    public function process(): bool
    {
        $uri = $this->redirect()->request()->get();
        if ($uri === '/' && !empty($_GET['artikul_1c'])) {
            $article = $_GET['artikul_1c'];
            $this->setTo('редирект на страницу с товаром ' . $article,301,false);
            /* if ($Product = $this->modx->getObject('msProductData', ['artikul_1c' => $article])) {
                 $url = $this->modx->makeUrl($Product->get('id'), 'web', '', 'full');
                 $this->setTo($url);
             }*/
        }
        return false;
    }
}
