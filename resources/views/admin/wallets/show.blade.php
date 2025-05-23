@extends('layouts.admin')

@section('title', __('messages.wallet_details'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Wallet Information Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-wallet"></i>
                        {{ __('messages.wallet_details') }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('wallets.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i>
                            {{ __('messages.back') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>{{ __('messages.wallet_id') }}:</strong></td>
                                    <td>#{{ $wallet->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{{ __('messages.current_balance') }}:</strong></td>
                                    <td>
                                        <span class="badge badge-success badge-lg">
                                            {{ number_format($wallet->total, 2) }} {{ __('messages.currency') }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>{{ __('messages.wallet_owner') }}:</strong></td>
                                    <td>
                                        @if($wallet->user)
                                            <span class="badge badge-info">{{ __('messages.user') }}</span>
                                            {{ $wallet->user->name }}
                                        @elseif($wallet->driver)
                                            <span class="badge badge-warning">{{ __('messages.driver') }}</span>
                                            {{ $wallet->driver->name }}
                                        @elseif($wallet->admin)
                                            <span class="badge badge-danger">{{ __('messages.admin') }}</span>  
                                            {{ $wallet->admin->name }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>{{ __('messages.created_at') }}:</strong></td>
                                    <td>{{ $wallet->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-4">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-success">
                                            <i class="fas fa-arrow-up"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ __('messages.total_deposits') }}</span>
                                            <span class="info-box-number">{{ number_format($totalDeposits, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-danger">
                                            <i class="fas fa-arrow-down"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ __('messages.total_withdrawals') }}</span>
                                            <span class="info-box-number">{{ number_format($totalWithdrawals, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info">
                                            <i class="fas fa-list"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ __('messages.transactions') }}</span>
                                            <span class="info-box-number">{{ $transactionCount }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-exchange-alt"></i>
                        {{ __('messages.wallet_transactions') }}
                    </h3>
                </div>
                <div class="card-body">
                    @if($wallet->transactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('messages.id') }}</th>
                                        <th>{{ __('messages.type') }}</th>
                                        <th>{{ __('messages.amount') }}</th>
                                        <th>{{ __('messages.note') }}</th>
                                        <th>{{ __('messages.processed_by') }}</th>
                                        <th>{{ __('messages.date') }}</th>
                                        <th>{{ __('messages.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($wallet->transactions as $transaction)
                                        <tr>
                                            <td>#{{ $transaction->id }}</td>
                                            <td>
                                                @if($transaction->deposit > 0)
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-arrow-up"></i>
                                                        {{ __('messages.deposit') }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-danger">
                                                        <i class="fas fa-arrow-down"></i>
                                                        {{ __('messages.withdrawal') }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($transaction->deposit > 0)
                                                    <span class="text-success">
                                                        +{{ number_format($transaction->deposit, 2) }} {{ __('messages.currency') }}
                                                    </span>
                                                @else
                                                    <span class="text-danger">
                                                        -{{ number_format($transaction->withdrawal, 2) }} {{ __('messages.currency') }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($transaction->note)
                                                    <span class="text-muted">{{ Str::limit($transaction->note, 50) }}</span>
                                                @else
                                                    <span class="text-muted">{{ __('messages.no_note') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($transaction->admin)
                                                    <span class="badge badge-danger">{{ __('messages.admin') }}</span>
                                                    {{ $transaction->admin->name }}
                                                @elseif($transaction->user)
                                                    <span class="badge badge-info">{{ __('messages.user') }}</span>
                                                    {{ $transaction->user->name }}
                                                @elseif($transaction->driver)
                                                    <span class="badge badge-warning">{{ __('messages.driver') }}</span>
                                                    {{ $transaction->driver->name }}
                                                @else
                                                    <span class="text-muted">{{ __('messages.system') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $transaction->created_at->format('Y-m-d H:i:s') }}</td>
                                            <td>
                                                 <div class="btn-group" role="group">
                                                    <a href="{{ route('transactions.edit', $transaction) }}" 
                                                       class="btn btn-warning btn-sm" 
                                                       title="{{ __('messages.edit') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('transactions.destroy', $transaction) }}" 
                                                          method="POST" 
                                                          style="display: inline;"
                                                          onsubmit="return confirm('{{ __('messages.delete_transaction_confirmation') }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-danger btn-sm" 
                                                                title="{{ __('messages.delete') }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">{{ __('messages.no_transactions') }}</h4>
                            <p class="text-muted">{{ __('messages.no_transactions_description') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection