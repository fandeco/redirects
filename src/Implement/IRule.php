<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 11.10.2022
 * Time: 16:35
 */

namespace App\Implement;


interface IRule
{
    public function process(): bool;

    public function case(): string;
}
