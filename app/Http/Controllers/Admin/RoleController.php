<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Models\Role;
use App\Query\LikeFilter;
use Database\Seeders\DefaultAdminSeeder;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = collect((new DefaultAdminSeeder)->permissions)->map(function ($item) {
            $attrs = explode('-', $item, 2);

            return ['name' => $item, 'group' => $attrs[1], 'group_name' => __("permissions.{$attrs[1]}"), 'title' => __("permissions.attributes.{$attrs[0]}", ['name' => ''])];
        })->toArray();

        return inertia('Roles',['permissions'=>$permissions]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getIndex()
    {
        $items = QueryBuilder::for(Role::class)->allowedFilters([
            'id',
            AllowedFilter::custom('name', new LikeFilter),
            AllowedFilter::custom('title', new LikeFilter),
        ])->allowedSorts([
            'id',
            'name',
            'title',
        ])->allowedIncludes([
            'users',
            'permissions'
        ])->withCount('users')->defaultSort('-id')->paginate(default_paginate());

        return response($items);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        $request->validate(['name' => 'unique:roles,name']);
        $created = Role::create([
            'name' => $request->input('name'),
            'title' => $request->input('title'),
            'guard_name' => 'web',
        ]);
        $created->syncPermissions(Arr::map($request->input('permissions'), fn ($item) => ! is_string($item) ? $item['name'] : $item));

        return response(['msg' => __('common.stored', ['name' => __('common.role')]), 'item' => Role::with(['permissions' => fn ($query) => $query->select(['id', 'name', 'title'])])->find($created->id)]);
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
    public function update(RoleRequest $request, string $id)
    {
        if ($id === 2) {
            return abort(Response::HTTP_NOT_FOUND);
        }
        $item = tap(Role::with(['permissions' => fn ($query) => $query->select(['id', 'name', 'title'])]), function ($query) use ($request, $id) {
            $query->find($id)->update([
                'name' => $request->input('name'),
                'title' => $request->input('title'),
            ]);
        });

        $newTimeStamp = clone $item;
        $newTimeStamp->find($id)->touch();

        $item->find($id)->syncPermissions(Arr::map($request->input('permissions'), fn ($item) => ! is_string($item) ? $item['name'] : $item));

        return response(['msg' => __('common.updated', ['name' => __('common.role')]), 'item' => $item->find($id)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findById($id, 'web');
        if (array_search($role->name, ['user', 'admin'])) {
            return abort(Response::HTTP_NOT_FOUND);
        }
        $users = $role->users;
        if (count($users) > 0) {
            $role->users()->assignRole('user');
            $role->users()->removeRole($role->id);
        }
        $role->delete();

        return response(['msg' => __('common.removed.item', ['name' => __('common.role')])]);
    }
}
