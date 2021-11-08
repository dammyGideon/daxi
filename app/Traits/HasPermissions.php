<?php

namespace App\Traits;

use App\Models\Permission;

trait HasPermissions {
    public function hasPermissionTo(...$permisions) 
    {
        //$user->hasPermissionTo('edit-user', 'edit-issue');

        return $this->roles()->whereIn('slug', $permisions)->count() ||
               $this->roles()->whereHas('permissions', function($q) use ($permisions) {
                  $q->whereIn('slug', $permisions);
               })->count();
    }

    private function getPermissionIdsBySlug($permisions) {
        return Permission::whereIn('slug', $permisions)->get()->pluck('id')->toArray();
    }

    public function givePermissionTo(...$permisions) 
    {
        $this->$permisions->attach($this->getPermissionIdsBySlug($permisions));
    }

    public function setPermissions(...$permisions) 
    {
        $this->$permisions->sync($this->getPermissionIdsBySlug($permisions));
    }

    public function detachPermissions(...$permisions) 
    {
        $this->$permisions->detach($this->getPermissionIdsBySlug($permisions));
    }

}