<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Fortify\PasswordValidationRules;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Query\LikeFilter;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    use PasswordValidationRules;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::get();

        return inertia('Users', ['roles' => $roles]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getIndex()
    {
        $items = QueryBuilder::for(User::class)->allowedFilters([
            AllowedFilter::custom('name', new LikeFilter),
            AllowedFilter::custom('email', new LikeFilter),
            'id',
            'status',
        ])->allowedSorts([
            'id',
            'status',
            'name',
            'email',
        ])->allowedIncludes([
            'roles',
        ])->defaultSort('-id')->paginate(default_paginate());

        return response($items);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $request->validate(['password' => ['required_if:has_password,true', 'confirmed'], 'mobile' => 'unique:users']);
        $item = User::create($request->except(['roles']));
        $item->syncRoles($request->input('roles'));

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
    public function update(Request $request, string $id)
    {
        $request->validate(['password' => ['required_if:has_password,true', 'confirmed'], 'mobile' => 'unique:users']);
        $item = User::find($id);
        $item->syncRoles($request->input('roles'));
        $item->update($request->except(['roles']));

        return response(['msg'=>__('common.updated',['name'=>__('validation.attributes.user')]),'item'=>$item->fresh(['roles'])]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::destroy($id);

        return response(['msg' => __('common.removed.item', ['name' => __('validation.attributes.user')])]);
    }
}
