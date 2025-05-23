@extends('layouts.admin')

@section('title', __('messages.add_transaction'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.add_transaction') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('wallet-transactions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                    </div>
                </div>
                
                <form action="{{ route('wallet-transactions.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="wallet_id" class="form-label">{{ __('messages.wallet') }} <span class="text-danger">*</span></label>
                                    <select class="form-select @error('wallet_id') is-invalid @enderror" 
                                            id="wallet_id" 
                                            name="wallet_id" 
                                            required>
                                        <option value="">{{ __('messages.select_wallet') }}</option>
                                        @foreach($wallets as $wallet)
                                            <option value="{{ $wallet->id }}" {{ old('wallet_id') == $wallet->id ? 'selected' : '' }}>
                                                #{{ $wallet->id }} - 
                                                @if($wallet->user)
                                                    {{ $wallet->user->name }} ({{ __('messages.user') }})
                                                @elseif($wallet->driver)
                                                    {{ $wallet->driver->name }} ({{ __('messages.driver') }})
                                                @elseif($wallet->admin)
                                                    {{ $wallet->admin->name ?? __('messages.admin') }} ({{ __('messages.admin') }})
                                                @endif
                                                - {{ number_format($wallet->total, 2) }} {{ __('messages.currency') }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('wallet_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h5 class="mb-3">{{ __('messages.transaction_type') }}</h5>
                                <p class="text-muted">{{ __('messages.select_transaction_type_help') }}</p>
                                
                                @error('transaction')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="deposit" class="form-label">{{ __('messages.deposit') }}</label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control @error('deposit') is-invalid @enderror" 
                                               id="deposit" 
                                               name="deposit" 
                                               value="{{ old('deposit', 0) }}" 
                                               step="0.01"
                                               min="0">
                                        <span class="input-group-text">{{ __('messages.currency') }}</span>
                                    </div>
                                    @error('deposit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-success">{{ __('messages.deposit_help') }}</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="withdrawal" class="form-label">{{ __('messages.withdrawal') }}</label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control @error('withdrawal') is-invalid @enderror" 
                                               id="withdrawal" 
                                               name="withdrawal" 
                                               value="{{ old('withdrawal', 0) }}" 
                                               step="0.01"
                                               min="0">
                                        <span class="input-group-text">{{ __('messages.currency') }}</span>
                                    </div>
                                    @error('withdrawal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-danger">{{ __('messages.withdrawal_help') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="note" class="form-label">{{ __('messages.note') }}</label>
                                    <textarea class="form-control @error('note') is-invalid @enderror" 
                                              id="note" 
                                              name="note" 
                                              rows="3"
                                              placeholder="{{ __('messages.transaction_note_placeholder') }}">{{ old('note') }}</textarea>
                                    @error('note')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h5 class="mb-3">{{ __('messages.performed_by') }}</h5>
                                <p class="text-muted">{{ __('messages.who_performed_transaction') }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="user_id" class="form-label">{{ __('messages.user') }}</label>
                                    <select class="form-select @error('user_id') is-invalid @enderror" 
                                            id="user_id" 
                                            name="user_id">
                                        <option value="">{{ __('messages.select_user') }}</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->phone }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="driver_id" class="form-label">{{ __('messages.driver') }}</label>
                                    <select class="form-select @error('driver_id') is-invalid @enderror" 
                                            id="driver_id" 
                                            name="driver_id">
                                        <option value="">{{ __('messages.select_driver') }}</option>
                                        @foreach($drivers as $driver)
                                            <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                                {{ $driver->name }} ({{ $driver->phone }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('driver_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="admin_id" class="form-label">{{ __('messages.admin') }}</label>
                                    <select class="form-select @error('admin_id') is-invalid @enderror" 
                                            id="admin_id" 
                                            name="admin_id">
                                        <option value="">{{ __('messages.select_admin') }}</option>
                                        @foreach($admins as $admin)
                                            <option value="{{ $admin->id }}" {{ old('admin_id') == $admin->id ? 'selected' : '' }}>
                                                {{ $admin->name ?? __('messages.admin') . ' #' . $admin->id }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('admin_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> {{ __('messages.save') }}
                        </button>
                        <a href="{{ route('wallet-transactions.index') }}" class="btn btn-secondary">
                            {{ __('messages.cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Ensure only deposit OR withdrawal is filled
document.addEventListener('DOMContentLoaded', function() {
    const depositInput = document.getElementById('deposit');
    const withdrawalInput = document.getElementById('withdrawal');
    
    depositInput.addEventListener('input', function() {
        if (this.value > 0) {
            withdrawalInput.value = 0;
        }
    });
    
    withdrawalInput.addEventListener('input', function() {
        if (this.value > 0) {
            depositInput.value = 0;
        }
    });
});
</script>
@endsection