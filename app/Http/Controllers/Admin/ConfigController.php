<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OperatorEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\VConfigRequest;
use App\Models\VConfig;
use App\Query\LikeFilter;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $operators = OperatorEnum::values();

        return inertia('Configs',['operators'=>$operators]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getIndex()
    {
        $items = QueryBuilder::for(VConfig::class)->allowedFilters([
            'id',
            AllowedFilter::custom('title',new LikeFilter),
            AllowedFilter::callback('server_id', function ($query, $value) {
                $query->whereHas('server', function ($query) use ($value) {
                    $query->where('name','like', "%$value%")
                        ->orWhere('latin_name','like', "%$value%");
                });
            }),
            AllowedFilter::callback('user_id', function ($query, $value) {
                $query->whereHas('user', function ($query) use ($value) {
                    $query->where('name','like', "%$value%");
                });
            }),
            'used',
            'status',
            'operator',
            AllowedFilter::scope('between_expired'),
            AllowedFilter::scope('between_created'),
        ])->allowedSorts([
            'id',
            'title',
            'server_id',
            'user_id',
            'used',
            'status',
            'operator',
            'expire',
        ])->allowedIncludes([
            'user',
            'server',
        ])->defaultSort('-id')->paginate(default_paginate());

        return response($items);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VConfigRequest $request)
    {
        $item = VConfig::create($request->validated());

        return back()->with(['msg'=>__('common.stored',['name'=>$item->title]),'item'=>VConfig::with([
            'user',
            'server',
        ])->find($item->id)]);
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
    public function update(VConfigRequest $request, string $id)
    {
        $item = VConfig::findOrFail($id);
        $item->update($request->validated());

        return back()->with(['msg'=>__('common.updated',['name'=>$item->title]),'item'=>$item->fresh([
            'user',
            'server',
        ])]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = VConfig::whereIn('id', array_map('intval', explode(',', $id)))->each(function ($item) {
            $item->delete();
        });

        return back()->with('msg',str_contains($id, ',') ? __('common.removed.items') : __('common.removed.item', ['name' => 'کانفیگ']));
    }
}
