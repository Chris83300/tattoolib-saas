<?php

namespace App\Http\Controllers;

class LegalController extends Controller
{
    public function mentionsLegales()
    {
        return view('legal.mentions-legales');
    }

    public function cgu()
    {
        return view('legal.cgu');
    }

    public function cgvArtistes()
    {
        return view('legal.cgv-artistes');
    }

    public function cgvClients()
    {
        return view('legal.cgv-clients');
    }

    public function politiqueConfidentialite()
    {
        return view('legal.politique-confidentialite');
    }

    public function politiqueCookies()
    {
        return view('legal.politique-cookies');
    }
}
