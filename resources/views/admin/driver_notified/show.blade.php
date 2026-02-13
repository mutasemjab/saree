@extends('layouts.admin')

@section('title', __('messages.driver_notified_details'))

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 font-weight-bold">
            <i class="fas fa-bell mr-2"></i>
            {{ __('messages.driver_notified_details') }}
            <small class="text-muted ml-2">{{ $order->number }}</small>
        </h4>
        <a href="{{ route('admin.driver-notified.index') }}" class="btn btn-default btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> {{ __('messages.back') }}
        </a>
    </div>

    {{-- Order Info Card --}}
    <div class="card card-outline card-primary mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-receipt mr-2"></i>{{ __('messages.order_info') }}
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6 col-md-3 mb-2">
                    <small class="text-muted d-block">{{ __('messages.order_number') }}</small>
                    <strong>{{ $order->number }}</strong>
                </div>
                <div class="col-6 col-md-3 mb-2">
                    <small class="text-muted d-block">{{ __('messages.order_status') }}</small>
                    <strong>{{ $order->status_text ?? '—' }}</strong>
                </div>
                @if($order->user)
                <div class="col-6 col-md-3 mb-2">
                    <small class="text-muted d-block">{{ __('messages.user_name') }}</small>
                    <strong>{{ $order->user->name }}</strong>
                </div>
                <div class="col-6 col-md-3 mb-2">
                    <small class="text-muted d-block">{{ __('messages.user_phone') }}</small>
                    <strong>{{ $order->user->phone ?? '—' }}</strong>
                </div>
                @endif
                <div class="col-6 col-md-3 mb-2">
                    <small class="text-muted d-block">{{ __('messages.pickup_location') }}</small>
                    <strong>{{ $order->pick_up_name ?? '—' }}</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="row mb-4">
        <div class="col-6 col-lg-2 mb-3">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $stats['total'] }}</h3>
                    <p>{{ __('messages.total_notified') }}</p>
                </div>
                <div class="icon"><i class="fas fa-bell"></i></div>
            </div>
        </div>
        <div class="col-6 col-lg-2 mb-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $stats['accepted'] }}</h3>
                    <p>{{ __('messages.total_accepted') }}</p>
                </div>
                <div class="icon"><i class="fas fa-check-circle"></i></div>
            </div>
        </div>
        <div class="col-6 col-lg-2 mb-3">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $stats['rejected'] }}</h3>
                    <p>{{ __('messages.total_rejected') }}</p>
                </div>
                <div class="icon"><i class="fas fa-times-circle"></i></div>
            </div>
        </div>
        <div class="col-6 col-lg-2 mb-3">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $stats['ignored'] }}</h3>
                    <p>{{ __('messages.total_ignored') }}</p>
                </div>
                <div class="icon"><i class="fas fa-minus-circle"></i></div>
            </div>
        </div>
        <div class="col-6 col-lg-2 mb-3">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{ $stats['notified'] }}</h3>
                    <p>{{ __('messages.pending_response') }}</p>
                </div>
                <div class="icon"><i class="fas fa-clock"></i></div>
            </div>
        </div>
    </div>

    {{-- Drivers Table --}}
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-car mr-2"></i>{{ __('messages.drivers_notified_list') }}
            </h5>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('messages.driver_name') }}</th>
                        <th>{{ __('messages.distance_km') }}</th>
                        <th>{{ __('messages.radius_km') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.notified_at') }}</th>
                        <th>{{ __('messages.responded_at') }}</th>
                        <th>{{ __('messages.response_time') }}</th>
                        @can('driverNotified-delete')
                        <th class="text-center">{{ __('messages.actions') }}</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse($driverNotifieds as $i => $record)
                    <tr class="{{ $record->status === 'accepted' ? 'table-success' : '' }}">
                        <td>{{ $i + 1 }}</td>

                        <td>
                            @if($record->driver)
                                <div class="d-flex align-items-center">
                                    <div class="mr-2">
                                        @if($record->status === 'accepted')
                                            <i class="fas fa-check-circle text-success"></i>
                                        @else
                                            <i class="fas fa-user text-muted"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-weight-bold">{{ $record->driver->name }}</div>
                                        <small class="text-muted">{{ $record->driver->phone ?? '' }}</small>
                                    </div>
                                </div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        <td>
                            @if($record->distance_km !== null)
                                <span class="badge badge-light border">
                                    <i class="fas fa-route mr-1 text-primary"></i>
                                    {{ number_format($record->distance_km, 2) }} km
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        <td>
                            @if($record->radius_km !== null)
                                <span class="badge badge-info">
                                    <i class="fas fa-circle-notch mr-1"></i>
                                    {{ number_format($record->radius_km, 1) }} km
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        <td>
                            @php
                                $statusConfig = [
                                    'notified' => ['badge-secondary', 'fa-bell'],
                                    'accepted' => ['badge-success',   'fa-check-circle'],
                                    'rejected' => ['badge-danger',    'fa-times-circle'],
                                    'ignored'  => ['badge-warning',   'fa-minus-circle'],
                                ];
                                $cfg = $statusConfig[$record->status] ?? ['badge-secondary', 'fa-circle'];
                            @endphp
                            <span class="badge {{ $cfg[0] }}">
                                <i class="fas {{ $cfg[1] }} mr-1"></i>
                                {{ __('messages.' . $record->status) }}
                            </span>
                        </td>

                        <td>
                            <small class="text-muted">
                                {{ $record->notified_at?->format('Y-m-d H:i:s') ?? '—' }}
                            </small>
                        </td>

                        <td>
                            <small class="text-muted">
                                {{ $record->responded_at?->format('Y-m-d H:i:s') ?? '—' }}
                            </small>
                        </td>

                        <td>
                            @if($record->notified_at && $record->responded_at)
                                <span class="text-success font-weight-bold">
                                    {{ $record->notified_at->diffInSeconds($record->responded_at) }}
                                    {{ __('messages.seconds') }}
                                </span>
                            @else
                                <span class="text-muted">{{ __('messages.no_response') }}</span>
                            @endif
                        </td>

                        @can('driverNotified-delete')
                        <td class="text-center">
                            <form action="{{ route('admin.driver-notified.destroy', $record->id) }}"
                                  method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                        title="{{ __('messages.delete') }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
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
@endsection

@push('scripts')
<script>
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