@extends('layouts.admin')

@section('title', __('messages.orders'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.orders') }}</h3>
                </div>
                
                <div class="card-body">
              

                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <form method="GET" action="{{ route('orders.index') }}" class="row g-3">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="search" 
                                           placeholder="{{ __('messages.search_order_number') }}" 
                                           value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <select name="status" class="form-control">
                                        <option value="">{{ __('messages.all_statuses') }}</option>
                                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                                        <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>{{ __('messages.accepted') }}</option>
                                        <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>{{ __('messages.on_the_way') }}</option>
                                        <option value="4" {{ request('status') == '4' ? 'selected' : '' }}>{{ __('messages.delivered') }}</option>
                                        <option value="5" {{ request('status') == '5' ? 'selected' : '' }}>{{ __('messages.cancelled_by_user') }}</option>
                                        <option value="6" {{ request('status') == '6' ? 'selected' : '' }}>{{ __('messages.cancelled_by_driver') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="payment_type" class="form-control">
                                        <option value="">{{ __('messages.all_payment_types') }}</option>
                                        <option value="1" {{ request('payment_type') == '1' ? 'selected' : '' }}>{{ __('messages.paid') }}</option>
                                        <option value="2" {{ request('payment_type') == '2' ? 'selected' : '' }}>{{ __('messages.unpaid') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="payment_method" class="form-control">
                                        <option value="">{{ __('messages.all_payment_methods') }}</option>
                                        <option value="1" {{ request('payment_method') == '1' ? 'selected' : '' }}>{{ __('messages.cash') }}</option>
                                        <option value="2" {{ request('payment_method') == '2' ? 'selected' : '' }}>{{ __('messages.visa') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-secondary">
                                        <i class="fas fa-search"></i> {{ __('messages.filter') }}
                                    </button>
                                    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i> {{ __('messages.clear') }}
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.order_number') }}</th>
                                    <th>{{ __('messages.customer') }}</th>
                                    <th>{{ __('messages.driver') }}</th>
                                    <th>{{ __('messages.status') }}</th>
                                    <th>{{ __('messages.final_price') }}</th>
                                    <th>{{ __('messages.payment_status') }}</th>
                                    <th>{{ __('messages.payment_method') }}</th>
                                    <th>{{ __('messages.created_at') }}</th>
                                    <th>{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td>
                                            <code class="bg-light px-2 py-1 rounded">{{ $order->number }}</code>
                                        </td>
                                        <td>
                                            @if($order->user)
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $order->user->photo_url }}" 
                                                         alt="{{ $order->user->name }}" 
                                                         class="rounded-circle me-2" 
                                                         width="30" height="30">
                                                    <div>
                                                        <div>{{ $order->user->name }}</div>
                                                        <small class="text-muted">{{ $order->user->phone }}</small>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">{{ __('messages.no_customer') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($order->driver)
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $order->driver->photo_url }}" 
                                                         alt="{{ $order->driver->name }}" 
                                                         class="rounded-circle me-2" 
                                                         width="30" height="30">
                                                    <div>
                                                        <div>{{ $order->driver->name }}</div>
                                                        <small class="text-muted">{{ $order->driver->phone }}</small>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">{{ __('messages.no_driver_assigned') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $order->status_color }}">
                                                {{ $order->status_text }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($order->final_price)
                                                <span class="fw-bold">{{ number_format($order->final_price, 2) }} {{ __('messages.currency') }}</span>
                                                @if($order->discount > 0)
                                                    <br><small class="text-muted">{{ __('messages.discount') }}: {{ number_format($order->discount, 2) }}</small>
                                                @endif
                                            @else
                                                <span class="text-muted">{{ __('messages.not_set') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $order->payment_type == 1 ? 'success' : 'warning' }}">
                                                {{ $order->payment_type == 1 ? __('messages.paid') : __('messages.unpaid') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $order->payment_method == 1 ? 'info' : 'primary' }}">
                                                {{ $order->payment_method == 1 ? __('messages.cash') : __('messages.visa') }}
                                            </span>
                                        </td>
                                        <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('orders.show', $order) }}" 
                                                   class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('orders.edit', $order) }}" 
                                                   class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                               
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">
                                            {{ __('messages.no_orders_found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $orders->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection