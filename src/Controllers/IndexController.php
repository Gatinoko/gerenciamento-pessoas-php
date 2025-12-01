<?php

namespace App\Controllers;

class IndexController
{
    public function index()
    {
        $htmlFilePath = './src/Pages/index.html';
        $htmlContent = file_get_contents($htmlFilePath);
        echo $htmlContent;
    }
}
