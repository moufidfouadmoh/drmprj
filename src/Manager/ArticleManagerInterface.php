<?php

namespace App\Manager;


interface ArticleManagerInterface extends BaseManagerInterface
{
    public function getArticleBySlugWithDetail($slug);
}