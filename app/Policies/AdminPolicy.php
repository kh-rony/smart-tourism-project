<?php

namespace App\Policies;

use App\Model\Admin\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function superAdmin(Admin $admin) {

        if ($admin->role->name == 'Super Admin') {
            return true;
        }

        return false;
    }

    public function placeAdmin(Admin $admin) {

        if ($admin->role->name == 'Place Admin') {
            return true;
        }
        return false;
    }

    public function hotelAdmin(Admin $admin) {

        if ($admin->role->name == 'Hotel Admin') {
            return true;
        }
        return false;
    }

    public function articleAdmin(Admin $admin) {

        if ($admin->role->name == 'Article Admin') {
            return true;
        }
        return false;
    }

    public function travelAgent(Admin $admin) {

        if ($admin->role->name == 'Travel Agent') {
            return true;
        }
        return false;
    }
}
