<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 11.10.2022
 * Time: 15:44
 */

namespace Tests;

use App\Redirect;
use App\Request;
use App\Rules\Article;
use App\Rules\Format;
use App\Rules\Index;
use App\Rules\Search;
use App\Rules\Slash;
use App\Site;
use App\Urls;
use Tests\TestCase;

class RequestTest extends TestCase
{

    public function testGetSearchRunFuntion()
    {

        $Site = new Site('https://dev2.massive.ru');
        $Request = new Request('/catalog/detskie--ljustra-potolochnaja.php', ['article' => 'FHR']);

        $Redirect = new Redirect($Site, $Request);

        $Urls = new Urls($Site);
        $Urls->add('/catalog/podvesnyie-svetilniki/', '/catalog/podvesnyie/');
        $Urls->add('https://dev2.massive.ru/catalog/detskie--ljustra-potolochnaja', 'https://fandeco.ru/catalog/ljustra-potolochnaja/interer-dlya-detskoj');

        $Redirect->setUrls($Urls);

        // Основные правила
        $Redirect->addRule(Format::class); // Срабатывает первым всегда чтобы определи что это статичны файл
        $Redirect->addRule(Index::class);
        $Redirect->addRule(Slash::class);

        // Правила остальные
        $Redirect->addRule(Search::class);
        $Redirect->addRule(Article::class);


        $Redirect->process();


        echo '<pre>';
        print_r($Redirect->toArray());
        die;

    }


    public function testGetSearchDomain()
    {

        $Site = new Site('https://dev2.massive.ru');
        $Request = new Request('/catalog/detskie--ljustra-potolochnaja.php', ['article' => 'FHR']);

        $Redirect = new Redirect($Site, $Request);

        $Urls = new Urls($Site);
        $Urls->add('/catalog/podvesnyie-svetilniki/', '/catalog/podvesnyie/');
        $Urls->add('https://dev2.massive.ru/catalog/detskie--ljustra-potolochnaja', 'https://fandeco.ru/catalog/ljustra-potolochnaja/interer-dlya-detskoj');

        $Redirect->setUrls($Urls);

        // Основные правила
        $Redirect->addRule(Format::class); // Срабатывает первым всегда чтобы определи что это статичны файл
        $Redirect->addRule(Index::class);
        $Redirect->addRule(Slash::class);

        // Правила остальные
        $Redirect->addRule(Search::class);
        $Redirect->addRule(Article::class);
        $Redirect->process();


        echo '<pre>';
        print_r($Redirect->toArray());
        die;

    }

    public function testGetSearch()
    {

        $Site = new Site('https://dev2.massive.ru');
        $Request = new Request('/catalog/podvesnyie-svetilniki.php', ['article' => 'FHR']);

        $Redirect = new Redirect($Site, $Request);

        $Urls = new Urls();
        $Urls->add('/catalog/podvesnyie-svetilniki/', '/catalog/podvesnyie/');
        $Redirect->setUrls($Urls);


        // Основные правила
        $Redirect->addRule(Format::class); // Срабатывает первым всегда чтобы определи что это статичны файл
        $Redirect->addRule(Index::class);
        $Redirect->addRule(Slash::class);

        // Правила остальные
        $Redirect->addRule(Search::class);
        $Redirect->addRule(Article::class);
        $Redirect->process();


        echo '<pre>';
        print_r($Redirect->toArray());
        die;

    }


    public function testGetChain()
    {

        $Site = new Site('https://dev2.massive.ru');
        $Request = new Request('/catalog.php', ['article' => 'FHR']);

        $Redirect = new Redirect($Site, $Request);

        // Основные правила
        $Redirect->addRule(Format::class); // Срабатывает первым всегда чтобы определи что это статичны файл
        $Redirect->addRule(Index::class);
        $Redirect->addRule(Slash::class);

        // Правила остальные
        $Redirect->addRule(Search::class);
        $Redirect->addRule(Article::class);
        $Redirect->process();


        echo '<pre>';
        print_r($Redirect->responseCode());
        print_r($Redirect->go());
        die;

    }

    public function testGetSlash()
    {

        $Site = new Site('https://dev2.massive.ru');
        $Request = new Request('/catalog', ['article' => 'FHR']);

        $Redirect = new Redirect($Site, $Request);

        // Основные правила
        $Redirect->addRule(Format::class); // Срабатывает первым всегда чтобы определи что это статичны файл
        $Redirect->addRule(Index::class);
        $Redirect->addRule(Slash::class);

        // Правила остальные
        $Redirect->addRule(Search::class);
        $Redirect->addRule(Article::class);
        $Redirect->process();


        echo '<pre>';
        print_r($Redirect->toArray());
        die;

    }


    public function testGetIndex()
    {

        $Site = new Site('https://dev2.massive.ru');
        $Request = new Request('/index.php', ['article' => 'FHR']);
        $Urls = new Urls();
        $Urls->add('/catalog/podvesnyie-svetilniki/', '/catalog/podvesnyie/');


        $Redirect = new Redirect($Site, $Request);
        $Redirect->setUrls($Urls);
        $Redirect->addRule(Format::class); // Срабатывает первым всегда чтобы определи что это статичны файл
        $Redirect->addRule(Slash::class);
        $Redirect->addRule(Index::class);
        $Redirect->addRule(Search::class);
        $Redirect->addRule(Article::class);
        $Redirect->process();


        echo '<pre>';
        print_r($Redirect->toArray());
        die;

    }

    public function testGetFormat()
    {

        $Site = new Site('https://dev2.massive.ru');
        $Request = new Request('/catalog/podvesnyie-svetilniki.svg');
        $Redirect = new Redirect($Site, $Request);
        $Redirect->addRule(Format::class);
        $Redirect->addRule(Search::class);
        $Redirect->addRule(Article::class);
        $Redirect->process();

        echo '<pre>';
        print_r($Redirect->toArray());
        die;

    }
}
