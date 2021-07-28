<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Contracts\Support\Renderable;

class OrganizationController extends Controller
{
    public function index(): Renderable
    {
        return view('organizations.index', [
            'organizations' => Organization::paginate()
        ]);
    }
}
