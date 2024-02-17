<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\Permission;
use App\Authorizable;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use Illuminate\Http\Request;
use Psy\CodeCleaner\ReturnTypePass;

class RoleController extends Controller
{
    // use Authorizable;

    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->permissions = Permission::where(['guard_name' => 'admin'])->get();
    }

    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();

        if (request()->ajax()) {
            return Datatables::of($roles)
                ->addColumn('action', function ($row) {
                    if ($row->name != "Admin"):
                        return "<button class='btn btn-danger role-delete btn-icon btn-round' data-id={$row->id}><i class='fa fa-trash'></i></button>";
                    else:
                        return "N/A";
                    endif;
                })
                ->rawColumns(['action'])
                ->make(true);
        } else {
            $breadcrumbs = [
                ['link' => "admin.home", 'name' => "Dashboard"], ['name' => "Roles"]
            ];
            return view('dashboard.roles.index', compact('roles', 'permissions', 'breadcrumbs'));
        }
    }

    public function create(Request $request)
    {
        $permissions = $this->permissions;
        $request->flash();
        return view('dashboard.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $this->validate($request, ['name' => 'required|unique:roles,name,NULL,id,deleted_at,NULL']);
        $role = Role::withTrashed()->firstOrNew(['name' => $request->name]);

        if ($role->trashed()) {
            // If a soft-deleted record with the same name exists, restore it
            $role->restore();
        }else{
            $role = Role::create([
                'name' => $request->name,
                'guard_name' => 'modifier'
            ]);

        }
      

        if ($role) {
            $permissions = $request->get('permissions', []);
            Permission::checkNewPermissions($permissions);
            if ($role->name === 'Admin') {
                $role->syncPermissions(Permission::all());
            } else {
                $role->syncPermissions($permissions);
            }
            return response()->json([
                'success' => true,
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'Role created successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'code' => 'danger',
                'title' => 'Error',
                'message' => 'Something went wrong.'
            ]);
        }
    }

    public function edit(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $permissions = $this->permissions;
        if ($role->name == Admin::ROLE_ADMIN) {
            return view('dashboard.inc.info', [
                'message' => Admin::ROLE_ADMIN . ' Role have access to all permissions by default.'
            ]);
        } else {
            return view('dashboard.roles.edit', compact('role', 'permissions'));
        }
    }

    public function update(Request $request, $id)
    {
        if ($role = Role::findOrFail($id)) {
            
            $permissions = $request->get('permissions', []);
            Permission::checkNewPermissions($permissions);
            // admin role has everything
            if ($role->name === 'Admin') {
                $role->syncPermissions(Permission::all());
            }else{

            }
                
        
            $permissions = $request->get('permissions', []);
            $role->syncPermissions($permissions);

            return response()->json([
                'success' => true,
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'Role updated successfully'
            ]);
        } else {
            flash()->error('Role with id ' . $id . ' note found.');
        }

        return redirect()->route('roles.index');
    }

    function destroy($id)
    {
        $role = Role::find($id);
        $role->delete();
        if ($role->save()):
            return response()->json([
                'success' => true,
                'code' => 'success',
                'title' => 'Congratulations',
                'message' => 'Role Delete successfully'
            ]);
        endif;
    }
}

