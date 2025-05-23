@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>{{ __('messages.Transactions_for_Wallet') }}: {{ $wallet->user ? $wallet->user->name : $wallet->admin->name }}</h1>
    <table class="table">
        <thead>
            <tr>
                <th>{{ __('messages.Deposit') }}</th>
                <th>{{ __('messages.Withdrawal') }}</th>
                <th>{{ __('messages.Note') }}</th>
                <th>{{ __('messages.Date') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->deposit }}</td>
                    <td>{{ $transaction->withdrawal }}</td>
                    <td>{{ $transaction->note }}</td>
                    <td>{{ $transaction->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('wallets.index') }}" class="btn btn-secondary">Back to Wallets</a>
</div>
@endsection
