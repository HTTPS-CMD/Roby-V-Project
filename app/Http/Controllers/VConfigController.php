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
        return response()->json(['configs'=>VConfig::where('user_id',Auth::id())->available()
            ->get()->map(fn($item) => ['id'=>$item->id,'config'=>$item->server->config_encrypted])]);
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
    public function update(Request $request)
    {
        $request->validate([
            'config_id' => 'required|exists:v_configs,id',
            'usage' => 'required|numeric|min:0'
        ]);

        $config = VConfig::findOrFail($request->config_id);

        if ($config->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $config->usage = $request->usage;

        if ($config->usage >= $config->total) {
            $config->status = 'expired';
        }

        $config->save();

        return response()->json(['message' => 'Usage updated'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VConfig $vConfig)
    {
        //
    }
}
