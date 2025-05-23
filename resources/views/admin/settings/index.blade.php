@extends('layouts.admin')

@section('title', __('messages.settings'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.settings') }}</h3>
                    <a href="{{ route('settings.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> {{ __('messages.add_setting') }}
                    </a>
                </div>
                
                <div class="card-body">
              

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.id') }}</th>
                                    <th>{{ __('messages.key') }}</th>
                                    <th>{{ __('messages.value') }}</th>
                                    <th>{{ __('messages.created_at') }}</th>
                                    <th>{{ __('messages.updated_at') }}</th>
                                    <th>{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($settings as $setting)
                                    <tr>
                                        <td>{{ $setting->id }}</td>
                                        <td>
                                            <code class="bg-light px-2 py-1 rounded">{{ $setting->key }}</code>
                                        </td>
                                        <td>
                                            <div class="setting-value" style="max-width: 300px;">
                                                @if(strlen($setting->value) > 50)
                                                    <span class="text-truncate d-block" title="{{ $setting->value }}">
                                                        {{ Str::limit($setting->value, 50) }}
                                                    </span>
                                                @else
                                                    {{ $setting->value }}
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $setting->created_at->format('Y-m-d H:i') }}</td>
                                        <td>{{ $setting->updated_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('settings.show', $setting) }}" 
                                                   class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('settings.edit', $setting) }}" 
                                                   class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                               
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            {{ __('messages.no_settings_found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $settings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.setting-value {
    word-break: break-word;
}
</style>
@endsection