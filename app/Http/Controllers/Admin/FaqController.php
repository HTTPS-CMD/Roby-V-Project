<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FaqRequest;
use App\Models\Faq;
use App\Query\LikeFilter;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Faqs');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getIndex()
    {
        $items = QueryBuilder::for(Faq::class)->allowedFilters([
            'id',
            AllowedFilter::custom('title',new LikeFilter),
            AllowedFilter::custom('content',new LikeFilter),
            'status',
        ])->allowedSorts([
            'id',
            'title',
            'content',
            'status',
            'sortable',
        ])->defaultSort('sortable')->paginate(default_paginate());

        return response()->json($items);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FaqRequest $request)
    {
        $item = Faq::create($request->validated());

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
    public function sort(Request $request)
    {
        Faq::massUpdate(
            values: $request->all(),
            uniqueBy: 'id'
        );

        return response()->noContent();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FaqRequest $request, string $id)
    {
        $item = Faq::findOrFail($id);
        $item->update($request->validated());

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Faq::findOrFail($id);
        $item->delete();

        return back();
    }
}
