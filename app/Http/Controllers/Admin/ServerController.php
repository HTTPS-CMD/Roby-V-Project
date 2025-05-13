<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServerRequest;
use App\Models\Server;
use App\Query\LikeFilter;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ServerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Servers');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getIndex()
    {
        $items = QueryBuilder::for(Server::class)->allowedFilters([
            AllowedFilter::custom('name',new LikeFilter),
            AllowedFilter::custom('latin_name',new LikeFilter),
            AllowedFilter::custom('location',new LikeFilter),
            'status',
            'created_at',
            'updated_at',
        ])->allowedSorts([
            'name',
            'latin_name',
            'traffic',
            'location',
            'status',
            'created_at',
            'updated_at',
        ])->allowedIncludes(['users','configs','tags'])->defaultSort('-id')
            ->withCount('configs')->paginate(default_paginate());

        return response($items);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ServerRequest $request)
    {
        $item = Server::create($request->except(['users','tags']));
        $item->users()->sync($request->input('users'));
        $item->syncTags($request->input('tags'));

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ServerRequest $request, string $id)
    {
        $item = Server::findOrFail($id);
        $item->users()->sync($request->input('users'));
        $item->syncTags($request->input('tags'));
        $item->update($request->except(['users','tags']));

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Server::findOrFail($id)->delete();

        return back();
    }
}
