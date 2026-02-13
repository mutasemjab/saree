@extends('layouts.admin')

@section('title', __('messages.view_order_notifications'))

@section('content')
<div class="container-fluid">

    {{-- ── Page Header ── --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold">
                <i class="fas fa-list me-2 text-primary"></i>
                {{ __('messages.view_order_notifications') }}
            </h4>
            <p class="text-muted mb-0 small mt-1">
                {{ __('messages.order_number') }}: <strong>{{ $order->number }}</strong>
            </p>
        </div>
        <a href="{{ route('admin.driver-notified.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> {{ __('messages.back') }}
        </a>
    </div>

    {{-- ── Order Info Card ── --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent border-bottom fw-semibold">
            <i class="fas fa-receipt me-2 text-primary"></i>
            {{ __('messages.order_info') }}
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <div class="small text-muted">{{ __('messages.order_number') }}</div>
                    <div class="fw-semibold">{{ $order->number }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="small text-muted">{{ __('messages.order_status') }}</div>
                    <div>{{ $order->status_text ?? '—' }}</div>
                </div>
                @if($order->user)
                <div class="col-6 col-md-3">
                    <div class="small text-muted">{{ __('messages.user_name') }}</div>
                    <div>{{ $order->user->name }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="small text-muted">{{ __('messages.user_phone') }}</div>
                    <div>{{ $order->user->phone ?? '—' }}</div>
                </div>
                @endif
                <div class="col-6 col-md-3">
                    <div class="small text-muted">{{ __('messages.pickup_location') }}</div>
                    <div>{{ $order->pick_up_name ?? '—' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Mini Stats ── --}}
    @php
        $byStatus = $driverNotifieds->groupBy('status');
    @endphp
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body py-3">
                    <div class="fs-4 fw-bold">{{ $driverNotifieds->count() }}</div>
                    <div class="small text-muted">{{ __('messages.total_notifications') }}</div>
                </div>
            </div>
        </div>
        @foreach(['accepted' => 'success', 'rejected' => 'danger', 'ignored' => 'warning', 'notified' => 'secondary'] as $s => $color)
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center" style="border-top: 3px solid var(--bs-{{ $color }}) !important;">
                <div class="card-body py-3">
                    <div class="fs-4 fw-bold text-{{ $color }}">{{ ($byStatus[$s] ?? collect())->count() }}</div>
                    <div class="small text-muted">{{ __('messages.' . $s) }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ── Drivers Table ── --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>{{ __('messages.driver_name') }}</th>
                            <th>{{ __('messages.distance_km') }}</th>
                            <th>{{ __('messages.radius_km') }}</th>
                            <th>{{ __('messages.status') }}</th>
                            <th>{{ __('messages.notified_at') }}</th>
                            <th>{{ __('messages.responded_at') }}</th>
                            <th>{{ __('messages.response_time') }}</th>
                            @can('driverNotified-edit')
                            <th class="text-center">{{ __('messages.actions') }}</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($driverNotifieds as $i => $record)
                        <tr class="{{ $record->status === 'accepted' ? 'table-success' : '' }}">
                            <td class="ps-3 text-muted small">{{ $i + 1 }}</td>

                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                         style="width:32px;height:32px;min-width:32px;">
                                        @if($record->status === 'accepted')
                                            <i class="fas fa-check text-success small"></i>
                                        @else
                                            <i class="fas fa-user text-primary small"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $record->driver->name ?? '—' }}</div>
                                        @if($record->driver)
                                        <div class="small text-muted">{{ $record->driver->phone ?? '' }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td>
                                @if($record->distance_km !== null)
                                    <span class="badge bg-light text-dark border">
                                        <i class="fas fa-route me-1 text-primary"></i>
                                        {{ number_format($record->distance_km, 2) }} km
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            <td>
                                @if($record->radius_km !== null)
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info">
                                        {{ number_format($record->radius_km, 1) }} km
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            <td>
                                @php
                                    $sc = [
                                        'notified' => ['bg-secondary', 'fa-bell'],
                                        'accepted' => ['bg-success',   'fa-check-circle'],
                                        'rejected' => ['bg-danger',    'fa-times-circle'],
                                        'ignored'  => ['bg-warning',   'fa-minus-circle'],
                                    ];
                                    $c = $sc[$record->status] ?? ['bg-secondary', 'fa-circle'];
                                @endphp
                                <span class="badge {{ $c[0] }}">
                                    <i class="fas {{ $c[1] }} me-1"></i>
                                    {{ __('messages.' . $record->status) }}
                                </span>
                            </td>

                            <td class="small text-muted">
                                {{ $record->notified_at?->format('Y-m-d H:i:s') ?? '—' }}
                            </td>

                            <td class="small text-muted">
                                {{ $record->responded_at?->format('Y-m-d H:i:s') ?? '—' }}
                            </td>

                            <td class="small">
                                @if($record->notified_at && $record->responded_at)
                                    <span class="text-success">
                                        {{ $record->notified_at->diffInSeconds($record->responded_at) }}s
                                    </span>
                                @else
                                    <span class="text-muted">{{ __('messages.no_response') }}</span>
                                @endif
                            </td>

                            @can('driverNotified-edit')
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('admin.driver-notified.show', $record->id) }}"
                                       class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.driver-notified.edit', $record->id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                            @endcan
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                {{ __('messages.no_data') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection