<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class RolesController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $roles = Role::where("name", "like", "%$keyword%")->paginate($perPage);
        } else {
            $roles = Role::latest()->paginate($perPage);
        }

        return view('pages.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $permissions = Permission::all();

        return view('pages.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permissions' => 'required'
        ]);

        $requestData = $request->all();

        $role = Role::create(['name' => $requestData['name']]);

        // attach permissions to role
        foreach ($requestData['permissions'] as $key => $value) {

            $role->givePermissionTo(Permission::findById($key));
        }

        return redirect('admin/roles')->with('flash_message', 'Role added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $role = Role::findOrFail($id);

        return view('pages.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);

        $permissions = Permission::all();

        $selected_permissions = [];

        foreach ($role->permissions as $permission) {

            array_push($selected_permissions, $permission->id);
        }

        return view('pages.roles.edit', compact('role', 'permissions', 'selected_permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => 'required'
        ]);

        $requestData = $request->all();
        
        $role = Role::findOrFail($id);

        $role->update(['name' => $requestData['name']]);

        // remove permissions from role
        foreach (Permission::all() as $permission) {

            $role->revokePermissionTo($permission);
        }


        // attach permissions to role
        foreach ($requestData['permissions'] as $key => $value) {

            $role->givePermissionTo(Permission::findById($key));
        }

        return redirect('admin/roles')->with('flash_message', 'Role updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Role::destroy($id);

        return redirect('admin/roles')->with('flash_message', 'Role deleted!');
    }
}