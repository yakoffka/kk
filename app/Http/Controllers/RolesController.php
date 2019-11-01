<?php

namespace App\Http\Controllers;

use App\Role;
use App\Permission;

class RolesController extends Controller
{
    public function __construct() {
        $this->middleware(['auth', 'permission:view_roles']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all()->toArray();
        return view('dashboard.adminpanel.roles.index', compact('roles', 'permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if ( auth()->user()->cannot('create_roles'), 403 );
        $permissions = Permission::all()->toArray();
        return view('dashboard.adminpanel.roles.create', compact('permissions'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Role $role)
    {
        abort_if ( auth()->user()->cannot('create_roles'), 403 );

        // validate main fields
        request()->validate([
            'name' => 'required|string|max:255|unique:roles',
            'display_name' => 'required|string|max:255|unique:roles',
            'description' => 'required|string|max:255|unique:roles',
        ]);

        // validate all possible permissions
        $permissions = Permission::all()->toArray();
        foreach ( $permissions as $permission ) {
            $arrToValidate[$permission['name']] = 'in:on,off';
        }
        request()->validate($arrToValidate);

        $role = Role::create([
            'name' => request('name'),
            'display_name' => request('display_name'),
            'description' => request('description'),
            // 'added_by_user_id' => auth()->user()->id,
        ]);

        return redirect()->route('roles.show', compact('role'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $permissions = Permission::all()->toArray();
        return view('dashboard.adminpanel.roles.show', compact('role', 'permissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        abort_if ( auth()->user()->cannot('edit_roles'), 403 );
        $permissions = Permission::all()->toArray();
        return view('dashboard.adminpanel.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Role $role)
    {
        abort_if ( auth()->user()->cannot('edit_roles'), 403 );

        // validate main fields
        request()->validate([
            'name' => 'required|string|max:255|unique:roles,name,'.$role->id.',',
            'display_name' => 'required|string|max:255|unique:roles,display_name,'.$role->id.',',
            'description' => 'required|string|max:255|unique:roles,description,'.$role->id.',',
        ]);

        // validate all possible permissions
        $permissions = Permission::all()->toArray();
        foreach ( $permissions as $permission ) {
            $arrToValidate[$permission['name']] = 'in:on,off';
        }
        request()->validate($arrToValidate);

        $role->setAviablePermissions();

        $role->update([
            'name' => request('name'),
            'display_name' => request('display_name'),
            'description' => request('description'),
        ]);

        return redirect()->route('roles.show', compact('role'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        abort_if ( auth()->user()->cannot('delete_roles'), 403 );

        if ( $role->id < 5  ) {
            return back()->withErrors([ __('is basic role and can not be removed') ]);
        }

        if ($role->users->count()) {
            return back()->withErrors([ __('role is assigned users. before removing it is necessary to take it away.') ]);
        }

        $role->forceDelete(); // and if forceDelete
        return redirect()->route('roles.index');
    }
}
