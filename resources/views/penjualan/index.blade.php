@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/penjualan/import') }}')" class="btn btn-info">Import Penjualan</button>
                 <a href="{{ url('/penjualan/penjualan/export_excel') }}" class="btn btn-primary">Export Excel</a>
                 <a href="{{ url('/penjualan/export_pdf') }}" class="btn btn-warning">Export PDF</a>
                 <button onclick="modalAction('{{ url('/penjualan/create_ajax') }}')" class="btn btn-success">Tambah Penjualan</button> 
            </div>
        </div>
        <div class="card-body">
            {{-- Alert untuk Success --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Alert untuk Error --}}
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-striped table-hover table-sm" id="table_penjualan">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Penjualan</th>
                        <th>Pembeli</th>
                        <th>Tanggal</th>
                        <th>User</th>
                        <th>Total Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
    data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        var dataPenjualan;

        $(document).ready(function() {
            dataPenjualan = $('#table_penjualan').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ url('penjualan/list') }}",
                    type: "POST",
                    dataType: "json",
                    data: function(d) {
                        // tidak ada filter tambahan
                    }
                },
                columns: [
                    {
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "penjualan_kode",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "pembeli",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "penjualan_tanggal",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "user_nama",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "total_harga",
                        className: "text-end",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "aksi",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endpush