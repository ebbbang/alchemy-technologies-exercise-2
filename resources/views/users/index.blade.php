@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">{{ __('Users') }}</div>

                    <div class="card-body">
                        @include('flash::message')

                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Organization</th>
                                    <th>Roles</th>
                                    @role('Stakeholder')
                                    <th>Action</th>
                                    @endrole
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $key => $user)
                                    <tr>
                                        <td>{{ $users->firstItem() + $key }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->organization->name }}</td>
                                        <td>
                                            @foreach($user->roles as $role)
                                                <span class="badge badge-warning">{{ $role->name }}</span>
                                            @endforeach
                                        </td>
                                        @role('Stakeholder')
                                        <td>
                                            <a href="{{ route('users.impersonate', $user) }}"
                                               class="btn btn-sm btn-primary">
                                                Login
                                            </a>
                                        </td>
                                        @endrole
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="float-right">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
