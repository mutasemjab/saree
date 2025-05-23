@extends('layouts.admin')

@section('title', __('messages.edit_user'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.edit_user') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                    </div>
                </div>
                
                <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
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
                                           value="{{ old('name', $user->name) }}" 
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
                                           value="{{ old('phone', $user->phone) }}" 
                                           required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>{{ __('messages.City') }}</label>
                                <select name="city_id" id="city" class="form-control">
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}"
                                            {{ old('city_id', isset($user) ? $user->city_id : '') == $city->id ? 'selected' : '' }}>
                                            {{ $city->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('city_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
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
                                    @if($user->photo)
                                        <div class="mb-2">
                                            <img src="{{ $user->photo_url }}" 
                                                 alt="{{ $user->name }}" 
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
                                    <select class="form-select @error('activate') is-invalid @enderror" 
                                            id="activate" 
                                            name="activate" 
                                            required>
                                        <option value="">{{ __('messages.select_status') }}</option>
                                        <option value="1" {{ old('activate', $user->activate) == '1' ? 'selected' : '' }}>
                                            {{ __('messages.active') }}
                                        </option>
                                        <option value="2" {{ old('activate', $user->activate) == '2' ? 'selected' : '' }}>
                                            {{ __('messages.inactive') }}
                                        </option>
                                    </select>
                                    @error('activate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="lat" class="form-label">{{ __('messages.latitude') }}</label>
                                    <input type="number" 
                                           class="form-control @error('lat') is-invalid @enderror" 
                                           id="lat" 
                                           name="lat" 
                                           value="{{ old('lat', $user->lat) }}" 
                                           step="any" 
                                           min="-90" 
                                           max="90">
                                    @error('lat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="lng" class="form-label">{{ __('messages.longitude') }}</label>
                                    <input type="number" 
                                           class="form-control @error('lng') is-invalid @enderror" 
                                           id="lng" 
                                           name="lng" 
                                           value="{{ old('lng', $user->lng) }}" 
                                           step="any" 
                                           min="-180" 
                                           max="180">
                                    @error('lng')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="fcm_token" class="form-label">{{ __('messages.fcm_token') }}</label>
                            <textarea class="form-control @error('fcm_token') is-invalid @enderror" 
                                      id="fcm_token" 
                                      name="fcm_token" 
                                      rows="3">{{ old('fcm_token', $user->fcm_token) }}</textarea>
                            @error('fcm_token')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> {{ __('messages.update') }}
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