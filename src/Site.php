<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 11.10.2022
 * Time: 15:46
 */

namespace App;


class Site
{
    /**
     * @var string
     */
    private $site_url;

    public function __construct(string $site_url)
    {
        $this->site_url = rtrim($site_url, '/');
    }

    public function get()
    {
        return $this->site_url;
    }

    public function getUrl(string $url)
    {
        return $this->site_url . $url;
    }
}
