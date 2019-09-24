<?php namespace BookStack\Auth;

use BookStack\Auth\Permissions\JointPermission;
use BookStack\Auth\Permissions\RolePermission;
use BookStack\Model;
use BookStack\Orz\Space;
use Illuminate\Support\Facades\DB;

class Role extends Model
{

    protected $fillable = ['display_name', 'description', 'external_auth_id'];

    /**
     * The roles that belong to the role.
     */
    public function users()
    {
        return $this->belongsToMany(User::class)->orderBy('name', 'asc');
    }
    
    public function space()
    {
        return $this->belongsTo(Space::class);
    }

    /**
     * Get all related JointPermissions.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jointPermissions()
    {
        return $this->hasMany(JointPermission::class);
    }

    /**
     * The RolePermissions that belong to the role.
     */
    public function permissions()
    {
        return $this->belongsToMany(RolePermission::class, 'permission_role', 'role_id', 'permission_id');
    }

    /**
     * Check if this role has a permission.
     * @param $permissionName
     * @return bool
     */
    public function hasPermission($permissionName)
    {
        $permissions = $this->getRelationValue('permissions');
        foreach ($permissions as $permission) {
            if ($permission->getRawAttribute('name') === $permissionName) {
                return true;
            }
        }
        return false;
    }

    /**
     * Add a permission to this role.
     * @param RolePermission $permission
     */
    public function attachPermission(RolePermission $permission)
    {
        $this->permissions()->attach($permission->id);
    }

    /**
     * Detach a single permission from this role.
     * @param RolePermission $permission
     */
    public function detachPermission(RolePermission $permission)
    {
        $this->permissions()->detach($permission->id);
    }

    /**
     * Get the role object for the specified role.
     * @param $roleName
     * @return Role
     */
    public static function getRole($roleName)
    {
        return static::where('name', '=', $roleName)->first();
    }

    /**
     * Get the role object for the specified system role.
     * @param $roleName
     * @return Role
     */
    public static function getSystemRole($roleName)
    {
        return static::where('system_name', '=', $roleName)->first();
    }

    /**
     * Get all visible roles
     * @return mixed
     */
    public static function visible()
    {
        return static::where('hidden', '=', false)->orderBy('name')->get();
    }
    
    public function getUsers()
    {
        $space = $this->space;
        $space_users = DB::table('space_user')
            ->where('space_id',$space->id)
            ->where('status', 1)
            ->pluck('user_id')->all();
        $users = $this->users;
        $all = [];
        foreach ($users as $user)
            if (in_array($user->id, $space_users))
                $all[] = $user;
        
        if ($this->name=='admin' && in_array($space->created_by, $space_users)) {
            $user = User::find($space->created_by);
            array_unshift($all,$user);
        }
        return $all;
    }
}
