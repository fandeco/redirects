<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 11.10.2022
 * Time: 16:07
 */

namespace App\Rules;


use App\Abstracts\Rule;
use App\Implement\IRule;

class Format extends Rule implements IRule
{
    protected $ext = null;
    protected $format = 'xml,csv,xlsx,xlx,txt,jpg,jpeg,png,gif,js,css,doc,ttf,woff,html,gif,svg,mp3,ogg,mpeg,zip,gz,bz2,rar,swf,ico';

    public function case(): string
    {
        return 'Отмена любых редиректов. Ссылка является статичной, так как совпала с расширением статичных файлов: ' . $this->ext;
    }

    public function process(): bool
    {
        $uri = $this->redirect()->request()->get();
        if (strripos($uri, '.') !== false) {
            $data = explode('.', $uri);
            $ext = array_pop($data);
            $ext = mb_strtolower($ext);
            $exts = array_flip(explode(',', $this->format));

            if (array_key_exists($ext, $exts)) {
                $this->ext = $ext;
                $this->setRedirectAllowed(false);
                return true;
            } else {
                $len = strlen($ext) + 1;
                $url = substr($uri, 0, -$len);
                $this->setTo($url);
            }
        }
        return false;
    }
}
