@extends('layouts.admin')

@section('title', __('messages.driver_notified'))

@section('content')
<div class="container-fluid">

    {{-- ── Page Header ── --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold">
            <i class="fas fa-bell me-2 text-primary"></i>
            {{ __('messages.driver_notified_list') }}
        </h4>
    </div>

    {{-- ── Alerts ── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Stats Cards ── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-2">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body py-3">
                    <div class="fs-3 fw-bold text-dark">{{ $stats['total'] }}</div>
                    <div class="small text-muted">{{ __('messages.total_notifications') }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2">
            <div class="card border-0 shadow-sm text-center h-100" style="border-top: 3px solid #6c757d !important;">
                <div class="card-body py-3">
                    <div class="fs-3 fw-bold text-secondary">{{ $stats['notified'] }}</div>
                    <div class="small text-muted">{{ __('messages.total_notified') }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2">
            <div class="card border-0 shadow-sm text-center h-100" style="border-top: 3px solid #198754 !important;">
                <div class="card-body py-3">
                    <div class="fs-3 fw-bold text-success">{{ $stats['accepted'] }}</div>
                    <div class="small text-muted">{{ __('messages.total_accepted') }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2">
            <div class="card border-0 shadow-sm text-center h-100" style="border-top: 3px solid #dc3545 !important;">
                <div class="card-body py-3">
                    <div class="fs-3 fw-bold text-danger">{{ $stats['rejected'] }}</div>
                    <div class="small text-muted">{{ __('messages.total_rejected') }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2">
            <div class="card border-0 shadow-sm text-center h-100" style="border-top: 3px solid #ffc107 !important;">
                <div class="card-body py-3">
                    <div class="fs-3 fw-bold text-warning">{{ $stats['ignored'] }}</div>
                    <div class="small text-muted">{{ __('messages.total_ignored') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Filters ── --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.driver-notified.index') }}">
                <div class="row g-2">

                    <div class="col-12 col-md-6 col-lg-2">
                        <select name="order_id" class="form-select form-select-sm">
                            <option value="">{{ __('messages.all_orders') }}</option>
                            @foreach($orders as $order)
                                <option value="{{ $order->id }}" {{ request('order_id') == $order->id ? 'selected' : '' }}>
                                    {{ $order->number }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-6 col-lg-2">
                        <select name="driver_id" class="form-select form-select-sm">
                            <option value="">{{ __('messages.all_drivers') }}</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}" {{ request('driver_id') == $driver->id ? 'selected' : '' }}>
                                    {{ $driver->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-6 col-lg-2">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">{{ __('messages.all_statuses') }}</option>
                            @foreach($statusOptions as $key => $label)
                                <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-6 col-lg-1">
                        <input type="number" name="radius_km" class="form-control form-control-sm"
                            placeholder="{{ __('messages.radius_km') }}"
                            value="{{ request('radius_km') }}" min="1" step="0.5">
                    </div>

                    <div class="col-12 col-md-6 col-lg-2">
                        <input type="date" name="date_from" class="form-control form-control-sm"
                            value="{{ request('date_from') }}">
                    </div>

                    <div class="col-12 col-md-6 col-lg-2">
                        <input type="date" name="date_to" class="form-control form-control-sm"
                            value="{{ request('date_to') }}">
                    </div>

                    <div class="col-12 col-md-6 col-lg-1 d-flex gap-1">
                        <button type="submit" class="btn btn-primary btn-sm flex-fill">
                            <i class="fas fa-search"></i>
                        </button>
                        <a href="{{ route('admin.driver-notified.index') }}" class="btn btn-outline-secondary btn-sm flex-fill">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- ── Table ── --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>{{ __('messages.order_number') }}</th>
                            <th>{{ __('messages.driver_name') }}</th>
                            <th>{{ __('messages.distance_km') }}</th>
                            <th>{{ __('messages.radius_km') }}</th>
                            <th>{{ __('messages.status') }}</th>
                            <th>{{ __('messages.notified_at') }}</th>
                            <th>{{ __('messages.responded_at') }}</th>
                            <th>{{ __('messages.response_time') }}</th>
                            @canany(['driverNotified-edit', 'driverNotified-delete'])
                            <th class="text-center">{{ __('messages.actions') }}</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($driverNotifieds as $record)
                        <tr>
                            <td class="ps-3 text-muted small">{{ $record->id }}</td>

                            <td>
                                @if($record->order)
                                    <a href="{{ route('admin.driver-notified.by-order', $record->order_id) }}"
                                       class="text-decoration-none fw-semibold">
                                        {{ $record->order->number }}
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            <td>
                                @if($record->driver)
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                             style="width:32px;height:32px;">
                                            <i class="fas fa-user text-primary small"></i>
                                        </div>
                                        <span>{{ $record->driver->name }}</span>
                                    </div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
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
                                        <i class="fas fa-circle-notch me-1"></i>
                                        {{ number_format($record->radius_km, 1) }} km
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            <td>
                                @php
                                    $statusConfig = [
                                        'notified' => ['bg-secondary', 'fa-bell'],
                                        'accepted' => ['bg-success',   'fa-check-circle'],
                                        'rejected' => ['bg-danger',    'fa-times-circle'],
                                        'ignored'  => ['bg-warning',   'fa-minus-circle'],
                                    ];
                                    $cfg = $statusConfig[$record->status] ?? ['bg-secondary', 'fa-circle'];
                                @endphp
                                <span class="badge {{ $cfg[0] }}">
                                    <i class="fas {{ $cfg[1] }} me-1"></i>
                                    {{ __('messages.' . $record->status) }}
                                </span>
                            </td>

                            <td class="small text-muted">
                                {{ $record->notified_at ? $record->notified_at->format('Y-m-d H:i:s') : '—' }}
                            </td>

                            <td class="small text-muted">
                                {{ $record->responded_at ? $record->responded_at->format('Y-m-d H:i:s') : '—' }}
                            </td>

                            <td class="small">
                                @if($record->notified_at && $record->responded_at)
                                    <span class="text-success">
                                        {{ $record->notified_at->diffInSeconds($record->responded_at) }}
                                        {{ __('messages.seconds') }}
                                    </span>
                                @else
                                    <span class="text-muted">{{ __('messages.no_response') }}</span>
                                @endif
                            </td>

                            @canany(['driverNotified-edit', 'driverNotified-delete'])
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    @can('driverNotified-table')
                                    <a href="{{ route('admin.driver-notified.show', $record->id) }}"
                                       class="btn btn-sm btn-outline-info" title="{{ __('messages.show') }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @endcan


                                    @can('driverNotified-delete')
                                    <form action="{{ route('admin.driver-notified.destroy', $record->id) }}"
                                          method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                title="{{ __('messages.delete') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                            @endcanany
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                {{ __('messages.no_data') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($driverNotifieds->hasPages())
            <div class="px-3 py-3 border-top">
                {{ $driverNotifieds->links() }}
            </div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Confirm delete
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('{{ __('messages.confirm_delete') }}')) {
                this.submit();
            }
        });
    });
</script>
@endpush