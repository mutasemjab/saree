@extends('layouts.admin')
@section('title')
الرئيسية
@endsection

@section('css')
<style>
.dashboard {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    padding: 20px;
}

.card {
    background-color: #fff;
    border-left: 5px solid #0dcaf0;
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    padding: 20px;
    text-align: center;
    transition: transform 0.3s ease;
}
.card:hover {
    transform: translateY(-5px);
}
.card h2 {
    font-size: 16px;
    color: #6c757d;
}
.card p {
    font-size: 28px;
    font-weight: bold;
    color: #0d6efd;
}
.driver-status {
    margin-top: 30px;
}
.driver-status table {
    width: 100%;
    border-collapse: collapse;
}
.driver-status th, .driver-status td {
    border: 1px solid #dee2e6;
    padding: 10px;
    text-align: center;
}
.driver-status th {
    background-color: #f8f9fa;
}
.status-online {
    color: green;
    font-weight: bold;
}
.status-offline {
    color: red;
    font-weight: bold;
}
</style>
@endsection


@section('contentheaderlink')
<a href="{{ route('admin.dashboard') }}"> الرئيسية </a>
@endsection

@section('contentheaderactive')
عرض
@endsection


@section('content')
<div class="dashboard">
    <div class="card">
        <h2>{{ __('messages.All Customers') }}</h2>
        <p>{{ $totalCustomers }}</p>
    </div>
    <div class="card">
        <h2>{{ __('messages.All Drivers') }}</h2>
        <p>{{ $totalDrivers }}</p>
    </div>
    <div class="card">
        <h2>{{ __('messages.Customers with Orders (This Month)') }}</h2>
        <p>{{ $customersWithOrdersThisMonth }}</p>
    </div>
    <div class="card">
        <h2>{{ __('messages.New Users This Month') }}</h2>
        <p>{{ $newUsersThisMonth }}</p>
    </div>
    <div class="card">
        <h2>{{ __('messages.Total Orders') }}</h2>
        <p>{{ $totalOrders }}</p>
    </div>
</div>

<div class="driver-status">
    <h3>🚗 حالة السائقين (Drivers Status)</h3>

    <form method="GET" action="{{ route('admin.dashboard') }}" style="margin-bottom: 15px;">
        <label for="status">تصفية حسب الحالة:</label>
        <select class="form-control" name="status" id="status" onchange="this.form.submit()">
            <option value="">الكل</option>
            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>متصل</option>
            <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>غير متصل</option>
        </select>
    </form>

    <table>
        <thead>
            <tr>
                <th>الاسم</th>
                <th>رقم الجوال</th>
                <th>الحالة</th>
                <th>المدينة</th>
            </tr>
        </thead>
        <tbody>
            @forelse($drivers as $driver)
            <tr>
                <td>{{ $driver->name }}</td>
                <td>{{ $driver->phone }}</td>
                <td>
                    <span class="{{ $driver->status == 1 ? 'status-online' : 'status-offline' }}">
                        {{ $driver->status == 1 ? 'متصل' : 'غير متصل' }}
                    </span>
                </td>
                <td>{{ $driver->city->name ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4">لا توجد سجلات</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection





