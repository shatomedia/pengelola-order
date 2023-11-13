<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = [
        'name',
        'guard_name',
    ];

    public function roleHasPermissions(): HasMany
    {
        return $this->hasMany(RoleHasPermission::class, 'role_id', 'id');
    }

    public function modelHasRoles(): HasMany
    {
        return $this->hasMany(ModelHasRole::class, 'role_id', 'id');
    }
}
