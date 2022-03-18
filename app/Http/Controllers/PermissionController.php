<?php
namespace App\Http\Controllers;

use App\Models\Permission;
use App\Role;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Http\Controllers\API\BaseController as BaseController;


class PermissionController extends BaseController
{

    public function index()
    {
        $resluts = Permission::all();

        return $this->sendResponse($resluts, 'Permissions retrieved successfully.');
    }

    public function show($id)
    {
        $results = Permission::find($id);

        if (is_null($results)) {
            return $this->sendError('Permission not found.');
        }
        return $this->sendResponse($results, 'Permission retrieved successfully.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'slug' => 'required|unique:permissions|max:255',
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {

            return $this->sendError($validator->errors(), ['error' => 'Validation Error']);
        }
        Permission::create($request->all());
        $success['name'] = $request->name;
        return $this->sendResponse($success, 'Permission Created successfully.');
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $permission = Permission::find($id);

        if ($permission == null) {
            return $this->sendError('Permission Not Found', ['error' => 'Database Error']);
        }

        $permission->slug = isset($input['slug']) ? $input['slug'] : $permission->slug;
        $permission->name = isset($input['name']) ? $input['name'] : $permission->name;

        $permission->save();

        return $this->sendResponse($permission, 'Permission updated successfully.');

    }

    public function destroy($id)
    {
        $permission = Permission::find($id);

        if ($permission == null) {
            return $this->sendError('Permission Not Found', ['error' => 'Database Error']);
        }

        $permission->delete();

        return $this->sendResponse($permission, 'Permission Deleted successfully.');

    }
    public function giveUserPermission($userId, $PermId)
    {

        $user = User::find($userId);
        if (is_null($user)) {
            return $this->sendError('User Not Found.', ['error' => 'Database Error']);
        }
        $user_perm = Permission::find($PermId);
        if (is_null($user_perm)) {
            return $this->sendError('Permission Not Found.', ['error' => 'Database Error']);
        }

        $user->permissions()->attach($user_perm);
        return response()->json("The User Given Permission.", 200);

    }

    public function permissionToRole($PermId, $RoleId)
    {

        $permission = Permission::find($PermId);
        if ($permission == null) {
            return $this->sendError('User Not Found.', ['error' => 'Database Error']);
        }
        $Role = Role::find($RoleId);
        if ($Role == null) {
            return $this->sendError('Role Not Found.', ['error' => 'Database Error']);
        }

        $Role->permissions()->attach($permission);

        return response()->json($permission, 200);

    }

    public function Permission()
    {
        $admin_permission = Permission::where('slug', 'edit-users')->first();
        $user_permission = Permission::where('slug', 'login')->first();

        $admin_role = new Role();
        $admin_role->slug = 'admin';
        $admin_role->name = 'Admin';
        $admin_role->save();
        $admin_role->permissions()->attach($admin_permission);

        $manager_role = new Role();
        $manager_role->slug = 'manager';
        $manager_role->name = 'Assistant Manager';
        $manager_role->save();
        $manager_role->permissions()->attach($user_permission);

        $coach_role = new Role();
        $coach_role->slug = 'coach';
        $coach_role->name = 'Coach';
        $coach_role->save();
        $coach_role->permissions()->attach($user_permission);

        $player_role = new Role();
        $player_role->slug = 'player';
        $player_role->name = 'Player';
        $player_role->save();
        $player_role->permissions()->attach($user_permission);

        $admin_role = Role::where('slug', 'admin')->first();
        $manager_role = Role::where('slug', 'manager')->first();
        $coach_role = Role::where('slug', 'coach')->first();
        $player_role = Role::where('slug', 'player')->first();

        $editUsers = new Permission();
        $editUsers->slug = 'edit-users';
        $editUsers->name = 'Edit Users';
        $editUsers->save();
        $editUsers->roles()->attach($admin_role);

        $user_permission = new Permission();
        $user_permission->slug = 'login';
        $user_permission->name = 'Log in';
        $user_permission->save();
        $user_permission->roles()->attach($player_role);
        $user_permission->roles()->attach($manager_role);
        $user_permission->roles()->attach($coach_role);

        return redirect()->back();
    }
}
