@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Organizations') }}</div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($organizations as $key => $organization)
                                    <tr>
                                        <td>{{ $organizations->firstItem() + $key }}</td>
                                        <td>{{ $organization->name }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="float-right">
                            {{ $organizations->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
