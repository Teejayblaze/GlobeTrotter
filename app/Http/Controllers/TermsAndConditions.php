<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TermsAndConditions extends Controller
{
    //

    public function index()
    {
        $tnc = view('termsandconditions.tnc')->render();
        return response()->json(['tnc' => $tnc]);
    }
}
