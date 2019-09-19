<?php namespace BookStack\Auth\Permissions;

use BookStack\Auth\Permissions;
use BookStack\Auth\Role;
use BookStack\Exceptions\PermissionsException;

class PermissionsRepo
{

    protected $permission;
    protected $role;
    protected $permissionService;

    protected $systemRoles = ['admin', 'public'];

    /**
     * PermissionsRepo constructor.
     * @param RolePermission $permission
     * @param Role $role
     * @param \BookStack\Auth\Permissions\PermissionService $permissionService
     */
    public function __construct(RolePermission $permission, Role $role, Permissions\PermissionService $permissionService)
    {
        $this->permission = $permission;
        $this->role = $role;
        $this->permissionService = $permissionService;
    }

    /**
     * Get all the user roles from the system.
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAllRoles()
    {
        return $this->role->all();
    }

    /**
     * Get all the roles except for the provided one.
     * @param Role $role
     * @return mixed
     */
    public function getAllRolesExcept(Role $role, $where = [])
    {
        return $this->role->where('id', '!=', $role->id)->where($where)->get();
    }

    /**
     * Get a role via its ID.
     * @param $id
     * @return mixed
     */
    public function getRoleById($id)
    {
        return $this->role->findOrFail($id);
    }

    /**
     * Save a new role into the system.
     * @param array $roleData
     * @return Role
     */
    public function saveNewRole($roleData, $id = 0)
    {
        $role = $this->role->newInstance($roleData);
        $role->name = str_replace(' ', '-', strtolower($roleData['display_name']));
        $role->space_id = $id;
        // Prevent duplicate names
        while ($this->role->where('name', '=', $role->name)->where('space_id',$id)->count() > 0) {
            $role->name .= strtolower(str_random(2));
        }
        $role->save();

        $permissions = isset($roleData['permissions']) ? array_keys($roleData['permissions']) : [];
        $this->assignRolePermissions($role, $permissions);
        $this->permissionService->buildJointPermissionForRole($role);
        return $role;
    }

    /**
     * Updates an existing role.
     * Ensure Admin role always have core permissions.
     * @param $roleId
     * @param $roleData
     * @throws PermissionsException
     */
    public function updateRole($roleId, $roleData)
    {
        $role = $this->role->findOrFail($roleId);
        
        //admin不能编辑
        if ($role->name=='admin' || $role->system_name == 'admin')
            return $role;

        $permissions = isset($roleData['permissions']) ? array_keys($roleData['permissions']) : [];
        if ($role->system_name === 'admin') {
            $permissions = array_merge($permissions, [
                'users-manage',
                'user-roles-manage',
                'restrictions-manage-all',
                'restrictions-manage-own',
                'settings-manage',
            ]);
        }

        $this->assignRolePermissions($role, $permissions);

        $role->fill($roleData);
        $role->save();
        $this->permissionService->buildJointPermissionForRole($role);
        return $role;
    }

    /**
     * Assign an list of permission names to an role.
     * @param Role $role
     * @param array $permissionNameArray
     */
    public function assignRolePermissions(Role $role, $permissionNameArray = [])
    {
        $permissions = [];
        $permissionNameArray = array_values($permissionNameArray);
        if ($permissionNameArray && count($permissionNameArray) > 0) {
            $permissions = $this->permission->whereIn('name', $permissionNameArray)->pluck('id')->toArray();
        }
        $role->permissions()->sync($permissions);
    }

    /**
     * Delete a role from the system.
     * Check it's not an admin role or set as default before deleting.
     * If an migration Role ID is specified the users assign to the current role
     * will be added to the role of the specified id.
     * @param $roleId
     * @param $migrateRoleId
     * @throws PermissionsException
     */
    public function deleteRole($roleId, $migrateRoleId)
    {
        $role = $this->role->findOrFail($roleId);

        // Prevent deleting admin role or default registration role.
        if ($role->system_name && in_array($role->system_name, $this->systemRoles)) {
            throw new PermissionsException(trans('errors.role_system_cannot_be_deleted'));
        } else if ($role->id == setting('registration-role')) {
            throw new PermissionsException(trans('errors.role_registration_default_cannot_delete'));
        }

        if ($migrateRoleId) {
            $newRole = $this->role->find($migrateRoleId);
            if ($newRole) {
                $users = $role->users->pluck('id')->toArray();
                $newRole->users()->sync($users);
            }
        }

        $this->permissionService->deleteJointPermissionsForRole($role);
        $copy_role = clone $role;
        $role->delete();
        return $copy_role;
    }
}
