@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Create Company') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('companies.store') }}">
                            @csrf

                            @role('Stakeholder')
                            <div class="form-group row">
                                <label for="organization_id"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Organization') }}</label>

                                <div class="col-md-6">
                                    <select name="organization_id" id="organization_id" autofocus
                                            class="form-control @error('organization_id') is-invalid @enderror"
                                    >
                                        <option value="">Select Organization</option>
                                        @foreach($organizations as $organization)
                                            <option value="{{ $organization->id }}"
                                                {{ old('organization_id') == $organization->id ? 'selected' : '' }}
                                            >
                                                {{ $organization->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('organization_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            @endrole

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text"
                                           class="form-control @error('name') is-invalid @enderror" name="name"
                                           value="{{ old('name') }}" required autocomplete="name">

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Create Company') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
