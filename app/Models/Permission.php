<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends \Spatie\Permission\Models\Permission
{
    public static function defaultPermissions()
    {
        return [
            'view_users',
            'add_users',
            'edit_users',
            'delete_users',

            'view_roles',
            'add_roles',
            'edit_roles',
            'delete_roles',

        ];
    }

    public static function checkNewPermissions($permissions)
    {
        foreach ($permissions as $permission) {
            if (empty($permission) || is_null($permission)) {
                continue;
            }
            // if (Permission::where(['name' => $permission, 'guard_name' => 'admin'])->count() > 0) {
            //     continue;
            // }
            if (Permission::where(['name' => $permission, 'guard_name' => 'admin'])->count() > 0) {
                continue;
            }
            // Permission::create([
            //     'name' => $permission,
            //     'guard_name' => 'admin'
            // ]);
            Permission::create([
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }
    }

}
