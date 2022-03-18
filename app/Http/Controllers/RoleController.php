<?php

namespace App\Http\Controllers;

use App\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController as BaseController;


class RoleController extends BaseController
{

    public function index()
    {
        $resluts = Role::all();

        return $this->sendResponse($resluts, 'ROles retrieved successfully.');
    }

    public function show($id)
    {
        $results = Role::find($id);

        if (is_null($results)) {
            return $this->sendError('ROle not found.');
        }
        return $this->sendResponse($results, 'ROle retrieved successfully.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'slug' => 'required|unique:roles|max:255',
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors(), ['error' => 'Validation Error']);
        }
        Role::create($request->all());

        $success['name'] = $request->all();

        return $this->sendResponse($success, 'Role Created successfully.');
    }

    public function update(Request $request, $id)
    {

        $input = $request->all();
        $role = Role::find($id);

        if ($role == null) {
            return $this->sendError('Role Not Found', ['error' => 'Database Error']);
        }

        $role->slug = isset($input['slug']) ? $input['slug'] : $role->slug;
        $role->name = isset($input['name']) ? $input['name'] : $role->name;

        $role->save();

        return $this->sendResponse($role, 'Role updated successfully.');
    }

    public function destroy($id)
    {

        $Role = Role::find($id);

        if ($Role == null) {
            return $this->sendError('Role Not Found', ['error' => 'Database Error']);
        }

        $Role->delete();

        return $this->sendResponse($Role, 'Role Deleted successfully.');

    }

    public function allPermissionForRole($RoleId)
    {

        $result = DB::table('permissions')
            ->join('roles_permissions', 'permissions.id', '=', 'roles_permissions.permission_id')
            ->where('roles_permissions.role_id', '=', $RoleId)
            ->select('permissions.*')
            ->get();
        if ($result == null || $result->isEmpty()) {
            return $this->sendError('Not Found', ['error' => 'Database Error']);
        }
        return $this->sendResponse($result, ' successful.');
    }

    public function giveUserRole($userId, $RoleId)
    {

        $user = User::where('id', $userId)->first();
        if ($user == null) {
            return $this->sendError('User Not Found', ['error' => 'Database Error']);
        }
        $user_role = Role::where('id', $RoleId)->first();
        if ($user_role == null) {
            return $this->sendError('Role Not Found', ['error' => 'Database Error']);
        }

        $user->roles()->attach($user_role);
        return response()->json("ok giveUserRole", 200);

    }

}
