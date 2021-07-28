@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        {{ __('Companies') }}
                        @unlessrole('User')
                        <a href="{{ route('companies.create') }}" class="btn btn-sm btn-primary float-right">
                            Create Company
                        </a>
                        @endunlessrole
                    </div>

                    <div class="card-body">
                        @include('flash::message')

                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Organization</th>
                                    @unlessrole('User')
                                    <th>Action</th>
                                    @endunlessrole
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($companies as $key => $company)
                                    <tr>
                                        <td>{{ $companies->firstItem() + $key }}</td>
                                        <td>{{ $company->name }}</td>
                                        <td>{{ $company->organization->name }}</td>
                                        @unlessrole('User')
                                        <td>
                                            <a href="{{ route('companies.edit', $company) }}"
                                               class="btn btn-sm btn-primary">
                                                Edit
                                            </a>

                                            <form action="{{ route('companies.destroy', $company) }}" class="d-inline"
                                                  method="post">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                        @endunlessrole
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="float-right">
                            {{ $companies->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
