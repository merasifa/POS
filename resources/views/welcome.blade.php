@extends('layouts.template')

@section('content')

    {{-- Kartu Statistik --}}
    <div class="card-container d-flex flex-wrap mb-4" style="gap: 20px;">
        <div class="card flex-fill p-4 text-center" style="min-width: 200px;">
            <h5>Total Pengguna</h5>
            <p class="mt-2" style="font-size: 2rem;">{{ $totalUser }}</p>
        </div>
        <div class="card flex-fill p-4 text-center" style="min-width: 200px;">
            <h5>Total Stok</h5>
            <p class="mt-2" style="font-size: 2rem;">{{ $totalStok }}</p>
        </div>        
        <div class="card flex-fill p-4 text-center" style="min-width: 200px;">
            <h5>Total Penjualan</h5>
            <p class="mt-2" style="font-size: 2rem;">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
        </div>        
    </div>

@endsection

@push('css')
    <style>
        .breadcrumb {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .dashboard-title {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .card-container .card {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .chart-container {
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@push('js')
@endpush
