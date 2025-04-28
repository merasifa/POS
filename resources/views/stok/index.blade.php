@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Stok Barang</h3>
            <div class="card-tools">
                 <a href="{{ url('/stok/stok/export_excel') }}" class="btn btn-primary">Export Excel</a>
                 <a href="{{ url('/stok/export_pdf') }}" class="btn btn-warning">Export PDF</a>
                 <button onclick="modalAction('{{ url('/stok/create_ajax') }}')" class="btn btn-success">Tambah Stok</button>
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

            {{-- Filtering --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Filter:</label>
                        <div class="col-3">
                            <select class="form-control" id="barang_id" name="barang_id">
                                <option value="">- Semua Barang -</option>
                                @foreach ($barang as $item)
                                    <option value="{{ $item->barang_id }}">{{ $item->barang_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Nama Barang</small>
                        </div>

                        <div class="col-3">
                            <select class="form-control" id="supplier_id" name="supplier_id">
                                <option value="">- Semua Supplier -</option>
                                @foreach ($supplier as $item)
                                    <option value="{{ $item->supplier_id }}">{{ $item->supplier_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Supplier</small>
                        </div>

                        <div class="col-md-3">
                            <select class="form-control stok_jumlah" id="stok_jumlah">
                                <option value="">- Semua Jumlah -</option>
                                <option value="kurang10"> < 10</option>
                                <option value="lebih100"> > 100</option>
                                <option value="habis"> 0</option>
                            </select>
                            <small class="form-text text-muted">Jumlah</small>
                        </div>
                        
                    </div>
                </div>
            </div>

            <table class="table table-bordered table-striped table-hover table-sm" id="table_stok">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Supplier</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
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
    function modalAction(url) {
        $.ajax({
            url: url,
            type: "GET",
            success: function(response) {
                $('#myModal').html(response);
                $('#myModal').modal('show');
            },
            error: function(xhr) {
                alert("Gagal menampilkan form. Error: " + xhr.status);
            }
        });
    }

    $(document).ready(function() {
        dataStok = $('#table_stok').DataTable({
            serverSide: true,
            ajax: {
                "url": "{{ url('stok/list') }}",
                "dataType": "json",
                "type": "POST",
                "data": function(d) {
                    d.barang_id = $('#barang_id').val();
                    d.supplier_id = $('#supplier_id').val();
                    d.stok_jumlah = $('#stok_jumlah').val(); 
                }
            },
            columns: [
                { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                { data: "barang.barang_kode" },
                { data: "barang.barang_nama" },
                { data: "supplier.supplier_nama" },
                { data: "stok_jumlah", className: "text-center" },
                { data: "stok_tanggal", className: "text-center" },
                { data: "aksi", className: "text-center", orderable: false, searchable: false }
            ]
        });

        $('#barang_id, #supplier_id, #stok_jumlah').on('change', function() {
            dataStok.ajax.reload();
        });
    });
</script>
@endpush