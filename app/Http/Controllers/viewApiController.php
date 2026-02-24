<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class viewApiController extends Controller
{
    public function index()
    {
        

        return view('welcome');
    }    


}
