@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/user/import') }}')" class="btn btn-info">Import User</button>
                <a href="{{ url('/user/user/export_excel') }}" class="btn btn-primary">Export Excel</a>
                <a href="{{ url('/user/export_pdf') }}" class="btn btn-warning">Export PDF</a>
                <button onclick="modalAction('{{ url('/user/create_ajax') }}')" class="btn btn-success">Tambah Data Ajax</button>  
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="row">
                <label class="col-1 control-label col-form-label">Filter:</label>
                <div class="col-3">
                    <select class="form-control" id="level_id" name="level_id" required>
                        <option value="">- Semua -</option>
                        @foreach ($level as $item)
                            <option value="{{ $item->level_id }}">{{ $item->level_nama }}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Level Pengguna</small>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover table-sm" id="table_user">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>USERNAME</th>
                        <th>NAMA</th>
                        <th>LEVEL PENGGUNA</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
        <!-- Tambahkan Modal -->
        <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true"></div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        function modalAction(url = '') {
             $('#myModal').load(url, function () {
                 $('#myModal').modal('show');
             });
         }
         var dataUser;
         $(document).ready(function () {
             dataUser = $('#table_user').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ url('user/list') }}",
                    'dataType': 'json',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "data": function (d) {
                        d.level_id = $('#level_id').val();
                    }
                },
                columns: [
                     { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                     { data: "username", orderable: true, searchable: true },
                     { data: "nama", orderable: true, searchable: true },
                     { data: "level.level_nama", orderable: false, searchable: false },
                     { data: "aksi", orderable: false, searchable: false }
                 ]
             });
 
             $('#level_id').on('change', function () {
                 dataUser.ajax.reload();
             });
             $('#level_id').on('change', function() {
 
             $('#myModal').on('hidden.bs.modal', function () {
                 dataUser.ajax.reload();
             });
         });
         });     
     </script>
 @endpush