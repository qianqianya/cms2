<?php

namespace App\Http\Controllers\Article;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redis;

class ArticleController extends Controller
{
    public function article(){
        return view('article.article');
    }
}