@extends('layouts.admin')

@section('title', __('messages.driver_notified'))

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 font-weight-bold">
            <i class="fas fa-bell mr-2"></i>
            {{ __('messages.driver_notified_list') }}
        </h4>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="row mb-4">
        <div class="col-6 col-lg-2 mb-3">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{ $stats['total_orders'] }}</h3>
                    <p>{{ __('messages.total_orders') }}</p>
                </div>
                <div class="icon"><i class="fas fa-receipt"></i></div>
            </div>
        </div>
        <div class="col-6 col-lg-2 mb-3">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $stats['total_notified'] }}</h3>
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
    </div>

    {{-- Filters --}}
    <div class="card card-outline card-primary mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.driver-notified.index') }}">
                <div class="row">
                    <div class="col-12 col-md-4 col-lg-3 mb-2">
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="{{ __('messages.order_number') }}"
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-12 col-md-4 col-lg-2 mb-2">
                        <input type="date" name="date_from" class="form-control form-control-sm"
                            value="{{ request('date_from') }}">
                    </div>
                    <div class="col-12 col-md-4 col-lg-2 mb-2">
                        <input type="date" name="date_to" class="form-control form-control-sm"
                            value="{{ request('date_to') }}">
                    </div>
                    <div class="col-12 col-md-4 col-lg-2 mb-2 d-flex" style="gap:6px;">
                        <button type="submit" class="btn btn-primary btn-sm flex-fill">
                            <i class="fas fa-search"></i> {{ __('messages.search') }}
                        </button>
                        <a href="{{ route('admin.driver-notified.index') }}" class="btn btn-default btn-sm flex-fill">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Orders Table --}}
    <div class="card card-outline card-primary">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('messages.order_number') }}</th>
                        <th>{{ __('messages.user_name') }}</th>
                        <th>{{ __('messages.pickup_location') }}</th>
                        <th class="text-center">{{ __('messages.total_notified') }}</th>
                        <th class="text-center">{{ __('messages.total_accepted') }}</th>
                        <th class="text-center">{{ __('messages.total_rejected') }}</th>
                        <th class="text-center">{{ __('messages.total_ignored') }}</th>
                        <th>{{ __('messages.order_status') }}</th>
                        <th>{{ __('messages.created_at') }}</th>
                        <th class="text-center">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>
                            <span class="font-weight-bold">{{ $order->number }}</span>
                        </td>

                        <td>
                            @if($order->user)
                                <div>{{ $order->user->name }}</div>
                                <small class="text-muted">{{ $order->user->phone }}</small>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        <td>
                            <small>{{ $order->pick_up_name ?? '—' }}</small>
                        </td>

                        <td class="text-center">
                            <span class="badge badge-info">{{ $order->driver_notified_count }}</span>
                        </td>

                        <td class="text-center">
                            <span class="badge badge-success">{{ $order->accepted_count }}</span>
                        </td>

                        <td class="text-center">
                            <span class="badge badge-danger">{{ $order->rejected_count }}</span>
                        </td>

                        <td class="text-center">
                            <span class="badge badge-warning">{{ $order->ignored_count }}</span>
                        </td>

                        <td>
                            @php
                                $statusMap = [
                                    1 => ['badge-secondary', 'Pending'],
                                    2 => ['badge-primary',   'Accepted'],
                                    3 => ['badge-info',      'On the way'],
                                    4 => ['badge-success',   'Delivered'],
                                    5 => ['badge-danger',    'Cancelled by user'],
                                    6 => ['badge-danger',    'Cancelled by driver'],
                                    7 => ['badge-dark',      'No drivers'],
                                ];
                                $s = $statusMap[$order->order_status] ?? ['badge-secondary', '—'];
                            @endphp
                            <span class="badge {{ $s[0] }}">{{ $s[1] }}</span>
                        </td>

                        <td>
                            <small class="text-muted">{{ $order->created_at->format('Y-m-d H:i') }}</small>
                        </td>

                        <td class="text-center">
                            @can('driverNotified-table')
                            <a href="{{ route('admin.driver-notified.show', $order->id) }}"
                               class="btn btn-sm btn-info" title="{{ __('messages.show') }}">
                                <i class="fas fa-eye"></i>
                            </a>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center py-5 text-muted">
                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                            {{ __('messages.no_data') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
        <div class="card-footer">
            {{ $orders->links() }}
        </div>
        @endif
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