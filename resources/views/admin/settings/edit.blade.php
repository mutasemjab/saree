@extends('layouts.admin')

@section('title', __('messages.edit_setting'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.edit_setting') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('settings.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                    </div>
                </div>
                
                <form action="{{ route('settings.update', $setting) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="key" class="form-label">{{ __('messages.key') }} <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('key') is-invalid @enderror" 
                                           id="key" 
                                           name="key" 
                                           value="{{ old('key', $setting->key) }}" 
                                           required>
                                    @error('key')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('messages.setting_key_help') }}</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="value" class="form-label">{{ __('messages.value') }} <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('value') is-invalid @enderror" 
                                              id="value" 
                                              name="value" 
                                              rows="4"
                                              required>{{ old('value', $setting->value) }}</textarea>
                                    @error('value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('messages.setting_value_help') }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Setting History -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">
                                            <i class="fas fa-history"></i> {{ __('messages.setting_history') }}
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>{{ __('messages.created_at') }}:</strong><br>
                                                <span class="text-muted">{{ $setting->created_at->format('Y-m-d H:i:s') }}</span><br>
                                                <small class="text-muted">{{ $setting->created_at->diffForHumans() }}</small>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>{{ __('messages.updated_at') }}:</strong><br>
                                                <span class="text-muted">{{ $setting->updated_at->format('Y-m-d H:i:s') }}</span><br>
                                                <small class="text-muted">{{ $setting->updated_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> {{ __('messages.update') }}
                        </button>
                        <a href="{{ route('settings.index') }}" class="btn btn-secondary">
                            {{ __('messages.cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection