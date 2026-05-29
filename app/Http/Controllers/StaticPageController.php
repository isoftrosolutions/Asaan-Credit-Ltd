<?php
namespace App\Http\Controllers;

class StaticPageController extends Controller
{
    public function about()
    {
        return $this->render('pages.about');
    }

    public function howItWorks()
    {
        return $this->render('pages.how-it-works');
    }

    public function legal()
    {
        return $this->render('pages.legal');
    }

    public function support()
    {
        return $this->render('pages.support');
    }
}
