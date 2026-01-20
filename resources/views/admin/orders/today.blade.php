@extends('layouts.admin')

@section('title', __('messages.Orders Todays'))

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">{{ __('messages.Orders Todays') }}</h3>
                    </div>

                    <div class="card-body">
                        <!-- Filters -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <form method="GET" action="{{ route('orders.today') }}" class="row g-3">
                                    <div class="col-md-2">
                                        <input type="text" class="form-control" name="search"
                                            placeholder="{{ __('messages.search_order_number') }}"
                                            value="{{ request('search') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <select name="status" class="form-control">
                                            <option value="">{{ __('messages.all_statuses') }}</option>
                                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>
                                                {{ __('messages.pending') }}</option>
                                            <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>
                                                {{ __('messages.accepted') }}</option>
                                            <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>
                                                {{ __('messages.on_the_way') }}</option>
                                            <option value="4" {{ request('status') == '4' ? 'selected' : '' }}>
                                                {{ __('messages.delivered') }}</option>
                                            <option value="5" {{ request('status') == '5' ? 'selected' : '' }}>
                                                {{ __('messages.cancelled_by_user') }}</option>
                                            <option value="6" {{ request('status') == '6' ? 'selected' : '' }}>
                                                {{ __('messages.cancelled_by_driver') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="city_id" class="form-control">
                                            <option value="">{{ __('messages.all_cities') }}</option>
                                            @foreach ($cities as $city)
                                                <option value="{{ $city->id }}"
                                                    {{ request('city_id') == $city->id ? 'selected' : '' }}>
                                                    {{ $city->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="payment_type" class="form-control">
                                            <option value="">{{ __('messages.all_payment_types') }}</option>
                                            <option value="1" {{ request('payment_type') == '1' ? 'selected' : '' }}>
                                                {{ __('messages.paid') }}</option>
                                            <option value="2" {{ request('payment_type') == '2' ? 'selected' : '' }}>
                                                {{ __('messages.unpaid') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="payment_method" class="form-control">
                                            <option value="">{{ __('messages.all_payment_methods') }}</option>
                                            <option value="1"
                                                {{ request('payment_method') == '1' ? 'selected' : '' }}>
                                                {{ __('messages.cash') }}</option>
                                            <option value="2"
                                                {{ request('payment_method') == '2' ? 'selected' : '' }}>
                                                {{ __('messages.visa') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-secondary">
                                            <i class="fas fa-search"></i> {{ __('messages.filter') }}
                                        </button>
                                        <a href="{{ route('orders.today') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-times"></i>
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
                                                @if ($order->user)
                                                    <div class="d-flex align-items-center">
                                                        <div>
                                                            <div>{{ $order->user->name }}</div>
                                                            <small>
                                                                <a href="tel:{{ $order->user->phone }}"
                                                                    class="text-decoration-none text-primary">
                                                                    <i class="fas fa-phone-alt"></i>
                                                                    {{ $order->user->phone }}
                                                                </a>
                                                            </small>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">{{ __('messages.no_customer') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($order->driver)
                                                    <div class="d-flex align-items-center">
                                                        <div>
                                                            <div>{{ $order->driver->name }}</div>
                                                            <small>
                                                                <a href="tel:{{ $order->driver->phone }}"
                                                                    class="text-decoration-none text-success">
                                                                    <i class="fas fa-phone-alt"></i>
                                                                    {{ $order->driver->phone }}
                                                                </a>
                                                            </small>
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
                                                @if ($order->final_price)
                                                    <span class="fw-bold">{{ number_format($order->final_price, 2) }}
                                                        {{ __('messages.currency') }}</span>
                                                    @if ($order->discount > 0)
                                                        <br><small class="text-muted">{{ __('messages.discount') }}:
                                                            {{ number_format($order->discount, 2) }}</small>
                                                    @endif
                                                @else
                                                    <span class="text-muted">{{ __('messages.not_set') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $order->payment_type == 1 ? 'success' : 'warning' }}">
                                                    {{ $order->payment_type == 1 ? __('messages.paid') : __('messages.unpaid') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $order->payment_method == 1 ? 'info' : 'primary' }}">
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

                                                    {{-- Cancel Button - Only show if order is not already cancelled or delivered --}}
                                                    @if (!in_array($order->order_status, [4, 5, 6]))
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            onclick="confirmCancelOrder({{ $order->id }}, '{{ $order->number }}')">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif
                                                </div>

                                                {{-- Hidden form for cancelling order --}}
                                                <form id="cancel-form-{{ $order->id }}"
                                                    action="{{ route('orders.cancel', $order) }}" method="POST"
                                                    style="display: none;">
                                                    @csrf
                                                    @method('PATCH')
                                                </form>
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

    {{-- Cancel Order Confirmation Modal --}}
    <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelOrderModalLabel">{{ __('messages.cancel_order') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('messages.are_you_sure_cancel_order') }}</p>
                    <p><strong>{{ __('messages.order_number') }}:</strong> <span id="orderNumberText"></span></p>
                    <p class="text-danger">{{ __('messages.this_action_cannot_be_undone') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('messages.no_cancel') }}</button>
                    <button type="button" class="btn btn-danger"
                        id="confirmCancelBtn">{{ __('messages.yes_cancel') }}</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        let orderToCancel = null;

        function confirmCancelOrder(orderId, orderNumber) {
            orderToCancel = orderId;
            document.getElementById('orderNumberText').textContent = orderNumber;

            // Show the modal
            const modal = new bootstrap.Modal(document.getElementById('cancelOrderModal'));
            modal.show();
        }

        document.getElementById('confirmCancelBtn').addEventListener('click', function() {
            if (orderToCancel) {
                document.getElementById('cancel-form-' + orderToCancel).submit();
            }
        });
    </script>
@endsection
