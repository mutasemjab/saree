@extends('layouts.admin')

@section('title', __('messages.add_setting'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.add_setting') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('settings.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                    </div>
                </div>
                
                <form action="{{ route('settings.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="key" class="form-label">{{ __('messages.key') }} <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('key') is-invalid @enderror" 
                                           id="key" 
                                           name="key" 
                                           value="{{ old('key') }}" 
                                           placeholder="{{ __('messages.setting_key_placeholder') }}"
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
                                              placeholder="{{ __('messages.setting_value_placeholder') }}"
                                              required>{{ old('value') }}</textarea>
                                    @error('value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('messages.setting_value_help') }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Common Settings Examples -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">
                                            <i class="fas fa-lightbulb"></i> {{ __('messages.common_setting_examples') }}
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>{{ __('messages.app_settings') }}:</h6>
                                                <ul class="list-unstyled small">
                                                    <li><code>app_name</code> - {{ __('messages.application_name') }}</li>
                                                    <li><code>app_version</code> - {{ __('messages.application_version') }}</li>
                                                    <li><code>maintenance_mode</code> - {{ __('messages.maintenance_mode') }}</li>
                                                    <li><code>max_users</code> - {{ __('messages.maximum_users') }}</li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>{{ __('messages.notification_settings') }}:</h6>
                                                <ul class="list-unstyled small">
                                                    <li><code>fcm_server_key</code> - {{ __('messages.fcm_server_key') }}</li>
                                                    <li><code>email_notifications</code> - {{ __('messages.email_notifications') }}</li>
                                                    <li><code>sms_gateway</code> - {{ __('messages.sms_gateway') }}</li>
                                                    <li><code>push_notifications</code> - {{ __('messages.push_notifications') }}</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> {{ __('messages.save') }}
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