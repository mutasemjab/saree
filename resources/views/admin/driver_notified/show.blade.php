@extends('layouts.admin')

@section('title', __('messages.driver_notified_details'))

@section('content')
<div class="container-fluid">

    {{-- ── Page Header ── --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold">
            <i class="fas fa-bell me-2 text-primary"></i>
            {{ __('messages.driver_notified_details') }}
        </h4>
        <a href="{{ route('admin.driver-notified.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> {{ __('messages.back') }}
        </a>
    </div>

    <div class="row g-4">

        {{-- ── Status Badge ── --}}
        <div class="col-12">
            @php
                $statusConfig = [
                    'notified' => ['bg-secondary', 'fa-bell',         'secondary'],
                    'accepted' => ['bg-success',   'fa-check-circle', 'success'],
                    'rejected' => ['bg-danger',    'fa-times-circle', 'danger'],
                    'ignored'  => ['bg-warning',   'fa-minus-circle', 'warning'],
                ];
                $cfg = $statusConfig[$driverNotified->status] ?? ['bg-secondary', 'fa-circle', 'secondary'];
            @endphp
            <div class="alert alert-{{ $cfg[2] }} d-flex align-items-center gap-2 mb-0">
                <i class="fas {{ $cfg[1] }} fa-lg"></i>
                <span class="fw-semibold">
                    {{ __('messages.status') }}: {{ __('messages.' . $driverNotified->status) }}
                </span>
            </div>
        </div>

        {{-- ── Driver Info ── --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom fw-semibold">
                    <i class="fas fa-user me-2 text-primary"></i>
                    {{ __('messages.driver_info') }}
                </div>
                <div class="card-body">
                    @if($driverNotified->driver)
                    <table class="table table-borderless table-sm mb-0">
                        <tr>
                            <td class="text-muted fw-semibold" width="40%">{{ __('messages.name') }}</td>
                            <td>{{ $driverNotified->driver->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">{{ __('messages.driver_name') }}</td>
                            <td>{{ $driverNotified->driver->phone ?? '—' }}</td>
                        </tr>
                    </table>
                    @else
                        <p class="text-muted mb-0">—</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Order Info ── --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom fw-semibold">
                    <i class="fas fa-receipt me-2 text-primary"></i>
                    {{ __('messages.order_info') }}
                </div>
                <div class="card-body">
                    @if($driverNotified->order)
                    <table class="table table-borderless table-sm mb-0">
                        <tr>
                            <td class="text-muted fw-semibold" width="40%">{{ __('messages.order_number') }}</td>
                            <td>
                                <a href="{{ route('admin.driver-notified.by-order', $driverNotified->order_id) }}"
                                   class="fw-semibold text-decoration-none">
                                    {{ $driverNotified->order->number }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">{{ __('messages.order_status') }}</td>
                            <td>{{ $driverNotified->order->status_text ?? '—' }}</td>
                        </tr>
                        @if($driverNotified->order->user)
                        <tr>
                            <td class="text-muted fw-semibold">{{ __('messages.user_name') }}</td>
                            <td>{{ $driverNotified->order->user->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">{{ __('messages.user_phone') }}</td>
                            <td>{{ $driverNotified->order->user->phone ?? '—' }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="text-muted fw-semibold">{{ __('messages.pickup_location') }}</td>
                            <td>{{ $driverNotified->order->pick_up_name ?? '—' }}</td>
                        </tr>
                    </table>
                    @else
                        <p class="text-muted mb-0">—</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Notification Details ── --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom fw-semibold">
                    <i class="fas fa-info-circle me-2 text-primary"></i>
                    {{ __('messages.driver_notified_details') }}
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm mb-0">
                        <tr>
                            <td class="text-muted fw-semibold" width="40%">{{ __('messages.distance_km') }}</td>
                            <td>
                                @if($driverNotified->distance_km !== null)
                                    <span class="badge bg-light text-dark border">
                                        <i class="fas fa-route me-1 text-primary"></i>
                                        {{ number_format($driverNotified->distance_km, 2) }} km
                                    </span>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">{{ __('messages.radius_km') }}</td>
                            <td>
                                @if($driverNotified->radius_km !== null)
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info">
                                        <i class="fas fa-circle-notch me-1"></i>
                                        {{ number_format($driverNotified->radius_km, 1) }} km
                                    </span>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">{{ __('messages.notified_at') }}</td>
                            <td class="small">{{ $driverNotified->notified_at?->format('Y-m-d H:i:s') ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">{{ __('messages.responded_at') }}</td>
                            <td class="small">{{ $driverNotified->responded_at?->format('Y-m-d H:i:s') ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">{{ __('messages.response_time') }}</td>
                            <td>
                                @if($driverNotified->notified_at && $driverNotified->responded_at)
                                    <span class="text-success fw-semibold">
                                        {{ $driverNotified->notified_at->diffInSeconds($driverNotified->responded_at) }}
                                        {{ __('messages.seconds') }}
                                    </span>
                                @else
                                    <span class="text-muted">{{ __('messages.no_response') }}</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- ── Action Buttons ── --}}
        <div class="col-12 d-flex gap-2">
         

            <a href="{{ route('admin.driver-notified.by-order', $driverNotified->order_id) }}"
               class="btn btn-outline-info">
                <i class="fas fa-list me-1"></i> {{ __('messages.view_order_notifications') }}
            </a>

            <a href="{{ route('admin.driver-notified.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> {{ __('messages.back') }}
            </a>
        </div>

    </div>
</div>
@endsection