<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define permission sets
        $permissionsForAll = [
//            Views only
            1, 3, 7, 11, 12, 18, 21, 26, 28, 32,
            38, 42, 45, 50, 52, 56, 60, 85,
            86, 90, 102, 104, 105, 110, 115,

            27,29,30,31,40,41,48,49,61,62,59
        ];

        $permissionsForRoleOne = [
            2,4,5,6,8,9,10,13,14,15,16,17,18,19,20,22,
            23,24,25,26,35,36,37,38,39,43,44,45,46,
            47,51,53,55,56,57,58,63,64,65,
            66,67,68,69,72,74,75,76,77,78,79,80,81,82,83,84,
            87,88,89,91,92,93,94,95,96,97,98,99,100,
            101,103,106,107,108,109,111,112,
            113,114,116,117,118,119,120,121,122,123,124
        ];

        $permissionsForRoleTwo = [
            15,18,70,73
        ];

        $permissionsForRoleThree = [
            2,15,18,70,73
        ];

        $permissionsForRoleFour = [
            // users_add,

        ];



        // Combine all permissions into one array for existence check
        $allPermissions = array_merge($permissionsForAll, $permissionsForRoleTwo, $permissionsForRoleThree, $permissionsForRoleFour, $permissionsForRoleOne);

        // Fetch existing permissions from the database
        $existingPermissions = DB::table('permissions')
            ->whereIn('id', $allPermissions)
            ->pluck('id')
            ->toArray();

        // Insert permissions for each role
        $roles = [1, 2, 3, 4];
        foreach ($roles as $role) {
            $permissionsToInsert = [];

            // Permissions for all roles
            $permissionsToInsert = array_merge($permissionsToInsert, $permissionsForAll);

            // Permissions for role 2
            if ($role === 2) {
                $permissionsToInsert = array_merge($permissionsToInsert, $permissionsForRoleTwo);
            }

            // Permissions for role 3
            if ($role === 3) {
                $permissionsToInsert = array_merge($permissionsToInsert, $permissionsForRoleThree);
            }

            // Permissions for role 4
            if ($role === 4) {
                $permissionsToInsert = array_merge($permissionsToInsert, $permissionsForRoleFour);
            }

            // Permissions for role 1
            if ($role === 1) {
                $permissionsToInsert = array_merge($permissionsToInsert, $permissionsForRoleOne);
            }

            // Filter out non-existent permissions
            $validPermissionsToInsert = array_filter($permissionsToInsert, function ($permission) use ($existingPermissions) {
                return in_array($permission, $existingPermissions);
            });

            // Insert role-permission associations
            DB::table('permission_role')->insert(
                array_map(function ($permission) use ($role) {
                    return [
                        'permission_id' => $permission,
                        'role_id' => $role,
                    ];
                }, $validPermissionsToInsert)
            );
        }
    }
}
