<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 11.10.2022
 * Time: 16:52
 */

namespace App;


class Urls
{
    private Site $site;

    public function __construct(Site $site)
    {
        $this->site = $site;
    }

    /**
     * @var null|array
     */
    protected $urls = null;
    /* @var Redirect|null $redirect */
    protected $redirect;

    public function add(string $source, string $target, int $code = 301)
    {
        $domain = null;
        // Проверка прямых редиректов
        if (strripos($source, 'https://') !== false || strripos($source, 'http://') !== false) {
            $data = parse_url($source);
            $domain = $data['scheme'] . '://' . $data['host'];
            $source = $data['path'];

        }

        $source = $this->clear($source);
        $target = $this->clear($target);
        $this->urls[$source] = [
            'domain' => $domain,
            'code' => $code,
            'target' => $target,
        ];
    }

    public function clear(string $url)
    {
        $url = trim($url, '/');
        return $url;
    }

    public function get(string $url)
    {
        $url = $this->clear($url);
        if (array_key_exists($url, $this->urls)) {
            $url = $this->urls[$url];
            $target = $this->clear($url['target']);
            $target = $target . '/';
            if (strripos($target, 'http') === false) {
                $target = '/' . $target;
            }
            $url['target'] = $target;
            return $url;
        }
        return false;
    }


    public function site()
    {
        return $this->site;
    }
}
