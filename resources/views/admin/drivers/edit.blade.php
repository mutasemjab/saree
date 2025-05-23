@extends('layouts.admin')

@section('title', __('messages.edit_driver'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.edit_driver') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('drivers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                    </div>
                </div>
                
                <form action="{{ route('drivers.update', $driver) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">{{ __('messages.name') }} <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $driver->name) }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="phone" class="form-label">{{ __('messages.phone') }} <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone', $driver->phone) }}" 
                                           required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="city" class="form-label">{{ __('messages.City') }} <span class="text-danger">*</span></label>
                                    <select name="city_id" id="city" class="form-control @error('city_id') is-invalid @enderror" required>
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}" {{ old('city_id', $driver->city_id) == $city->id ? 'selected' : '' }}>
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('city_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="identity_number" class="form-label">{{ __('messages.identity_number') }} <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('identity_number') is-invalid @enderror" 
                                           id="identity_number" 
                                           name="identity_number" 
                                           value="{{ old('identity_number', $driver->identity_number) }}" 
                                           required>
                                    @error('identity_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="plate_number" class="form-label">{{ __('messages.plate_number') }} <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('plate_number') is-invalid @enderror" 
                                           id="plate_number" 
                                           name="plate_number" 
                                           value="{{ old('plate_number', $driver->plate_number) }}" 
                                           required>
                                    @error('plate_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="car_type" class="form-label">{{ __('messages.Car type') }} <span class="text-danger">*</span></label>
                                    <select class="form-control @error('car_type') is-invalid @enderror" 
                                            id="car_type" 
                                            name="car_type" 
                                            required>
                                        <option value="">{{ __('messages.select_Car type') }}</option>
                                        <option value="1" {{ old('car_type', $driver->car_type) == '1' ? 'selected' : '' }}>
                                            {{ __('messages.car') }}
                                        </option>
                                        <option value="2" {{ old('car_type', $driver->car_type) == '2' ? 'selected' : '' }}>
                                            {{ __('messages.motosycle') }}
                                        </option>
                                    </select>
                                    @error('car_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="password" class="form-label">{{ __('messages.password') }}</label>
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password">
                                    <small class="form-text text-muted">{{ __('messages.leave_blank_to_keep_current') }}</small>
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
                                    @if($driver->photo)
                                        <div class="mb-2">
                                            <img src="{{ asset('assets/admin/uploads') . '/' . $driver->photo }}" 
                                                 alt="{{ $driver->name }}" 
                                                 class="img-thumbnail" 
                                                 width="100">
                                        </div>
                                    @endif
                                    <input type="file" 
                                           class="form-control @error('photo') is-invalid @enderror" 
                                           id="photo" 
                                           name="photo" 
                                           accept="image/*">
                                    @error('photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="activate" class="form-label">{{ __('messages.status') }} <span class="text-danger">*</span></label>
                                    <select class="form-control @error('activate') is-invalid @enderror" 
                                            id="activate" 
                                            name="activate" 
                                            required>
                                        <option value="1" {{ old('activate', $driver->activate) == '1' ? 'selected' : '' }}>
                                            {{ __('messages.active') }}
                                        </option>
                                        <option value="2" {{ old('activate', $driver->activate) == '2' ? 'selected' : '' }}>
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
                            <i class="fas fa-save"></i> {{ __('messages.update') }}
                        </button>
                        <a href="{{ route('drivers.index') }}" class="btn btn-secondary">
                            {{ __('messages.cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection