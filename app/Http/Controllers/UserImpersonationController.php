<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class UserImpersonationController extends Controller
{
    public function store(User $user): RedirectResponse
    {
        if (!is_null(Auth::user()->organization_id)) {
            flash('You do not have permission to Login as another users.')->error();

            return redirect()->back();
        }

        Auth::user()->impersonate($user);

        return redirect()->route('home');
    }
}
