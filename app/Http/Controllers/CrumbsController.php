<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CrumbsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tags = [];
        
        foreach($request->crumbs as $crumb) {
            $tags[] = ucfirst($crumb);
        }

        return $tags;
    }
}
