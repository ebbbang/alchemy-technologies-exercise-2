<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Organization;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CompaniesController extends Controller
{
    public function index(): Renderable
    {
        return view('companies.index', [
            'companies' => Company::with('organization')
                ->when($this->user->hasAnyRole('Admin', 'User'), function (Builder $query) {
                    return $query->where('organization_id', $this->user->organization_id);
                })
                ->paginate()
        ]);
    }

    public function create(): Renderable
    {
        return view('companies.create', [
            'organizations' => Organization::all()
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $organizationId = $this->user->organization_id ?: $request->input('organization_id');

        $this->validate($request, [
            'organization_id' => [
                Rule::requiredIf(!$this->user->organization_id),
                Rule::exists('organizations', 'id')
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('companies', 'name')->where('organization_id', $organizationId)
            ]
        ]);

        Company::create([
            'organization_id' => $organizationId,
            'name' => $request->input('name'),
        ]);

        flash('Company created successfully.')->success();

        return redirect()->route('companies.index');
    }

    public function show(Company $company)
    {
        //
    }

    public function edit(Company $company): Renderable
    {
        return view('companies.edit', [
            'organizations' => Organization::all(),
            'company' => $company
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function update(Request $request, Company $company): RedirectResponse
    {
        if ($this->user->organization_id && $this->user->organization_id != $company->organization_id) {
            flash('You do not have permission to perform this action.')->success();

            return redirect()->route('companies.index');
        }

        $organizationId = $this->user->organization_id ? $company->organization_id : $request->input('organization_id');

        $this->validate($request, [
            'organization_id' => [
                Rule::requiredIf(!$this->user->organization_id),
                Rule::exists('organizations', 'id')
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('companies', 'name')
                    ->where('organization_id', $organizationId)
                    ->ignore($company)
            ]
        ]);

        $company->update([
            'organization_id' => $organizationId,
            'name' => $request->input('name'),
        ]);

        flash('Company updated successfully.')->success();

        return redirect()->route('companies.index');
    }

    public function destroy(Company $company): RedirectResponse
    {
        if ($this->user->organization_id && $this->user->organization_id != $company->organization_id) {
            flash('You do not have permission to perform this action.')->success();
        } else {
            $company->delete();

            flash('Company created successfully.')->success();
        }

        return redirect()->route('companies.index');
    }
}
