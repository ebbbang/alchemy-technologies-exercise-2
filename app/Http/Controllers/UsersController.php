<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Builder;

class UsersController extends Controller
{
    public function index(Organization $organization): Renderable
    {
        return view('users.index', [
            'users' => User::with('organization')
                ->exceptStakeholders()
                ->when($this->user->hasRole('Admin'), function (Builder $query) {
                    return $query->where('organization_id', $this->user->organization_id);
                })
                ->with('roles')
                ->paginate()
        ]);
    }
}
