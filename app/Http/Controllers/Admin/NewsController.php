<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsRequest;
use App\Models\News;
use App\Query\LikeFilter;
use GuzzleHttp\Psr7\Query;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('News');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getIndex()
    {
        $items = QueryBuilder::for(News::class)->allowedFilters([
            'id',
            AllowedFilter::custom('title',new LikeFilter),
            AllowedFilter::custom('content',new LikeFilter),
            'status',
            AllowedFilter::callback('user_id',function($query,$value){
                $query->whereHas('user', function ($query) use ($value) {
                    $query->where('name','like', "%$value%");
                });
            })
        ])->allowedSorts([
            'id',
            'title',
            'status',
            'user_id'
        ])->allowedIncludes([
            'user'
        ])->defaultSort('-id')->paginate(default_paginate());

        return response($items);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NewsRequest $request)
    {
        $item = News::create(array_merge($request->validated(),['user_id'=>Auth::id()]));

        return back()->with('msg',__('common.stored',['name'=>$item->title]));
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
    public function update(NewsRequest $request, string $id)
    {
        $item = News::findOrFail($id)->update($request->validated());

        return back()->with('msg',__('common.updated',['name'=>$item->title]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        News::withTrashed()->whereIn('id', array_map('intval', explode(',', $id)))->each(function ($item) {
            if (is_null($item->deleted_at)) {
                $item->delete();
            } else {
                $item->forceDelete();
            }
        });

        return back()->with('msg',str_contains($id, ',') ? __('common.removed.items') : __('common.removed.item', ['name' => 'خبر']));
    }
}
