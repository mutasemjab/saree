@extends('layouts.admin')

@section('title', __('messages.add_user'))

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('messages.add_user') }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                            </a>
                        </div>
                    </div>

                    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                  <div class="col-md-4">
                                    <div class="form-group mb-3">
                                    <label for="phone" class="form-label">{{ __('messages.City') }} <span class="text-danger">*</span></label>
                                        <select name="city_id" id="city" class="form-control">
                                            @foreach ($cities as $city)
                                                <option value="{{ $city->id }}"> {{ $city->name }}</option>
                                            @endforeach
                                        </select>

                                        @error('city')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label">{{ __('messages.name') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="phone" class="form-label">{{ __('messages.phone') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" value="{{ old('phone') }}" required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                          

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="password" class="form-label">{{ __('messages.password') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            id="password" name="password" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="photo" class="form-label">{{ __('messages.photo') }}</label>
                                        <input type="file" class="form-control @error('photo') is-invalid @enderror"
                                            id="photo" name="photo" accept="image/*">
                                        @error('photo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="activate" class="form-label">{{ __('messages.status') }} <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control @error('activate') is-invalid @enderror" id="activate"
                                            name="activate" required>
                                            <option value="">{{ __('messages.select_status') }}</option>
                                            <option value="1" {{ old('activate') == '1' ? 'selected' : '' }}>
                                                {{ __('messages.active') }}
                                            </option>
                                            <option value="2" {{ old('activate') == '2' ? 'selected' : '' }}>
                                                {{ __('messages.inactive') }}
                                            </option>
                                        </select>
                                        @error('activate')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                           
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ __('messages.save') }}
                            </button>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                {{ __('messages.cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
