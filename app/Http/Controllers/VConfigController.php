<?php

namespace App\Http\Controllers;

use App\Models\VConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['configs'=>VConfig::where('user_id',Auth::id())/*->where('status',true)
          ->whereColumn('usage','<','total')
       ->whereDate('expire','>',now())*/->get()->map(fn($item) => $item->server->config_encrypted)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(VConfig $vConfig)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VConfig $vConfig)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VConfig $vConfig)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VConfig $vConfig)
    {
        //
    }
}
