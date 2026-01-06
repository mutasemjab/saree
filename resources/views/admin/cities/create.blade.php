@extends('layouts.admin')

@section('title', __('messages.add_city'))

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3>{{ __('messages.add_city') }}</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('cities.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('messages.name') }}</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">{{ __('messages.save') }}</button>
                        <a href="{{ route('cities.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
