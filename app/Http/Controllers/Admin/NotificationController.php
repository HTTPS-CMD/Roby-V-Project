<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NotificationRequest;
use App\Models\Notification;
use App\Query\LikeFilter;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Notifications');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getIndex()
    {
        $items = QueryBuilder::for(Notification::class)->allowedFilters([
            'id',
            AllowedFilter::custom('title',new LikeFilter),
            'all_users'
        ])->allowedSorts([
            'id',
            'title',
            'status',
            'all_users',
        ])->allowedIncludes(['users'])->defaultSort('-id')->paginate(default_paginate());

        return response()->json($items);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NotificationRequest $request)
    {
        $item = Notification::create($request->validated());

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
    public function update(NotificationRequest $request, string $id)
    {
        $item = Notification::findOrFail($id);
        $item->update($request->validated());

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Notification::findOrFail($id);
        $item->delete();

        return back();
    }
}
