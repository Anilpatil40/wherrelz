@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card p-3 bg-light">
            <div class="d-flex align-items-center">
                <h4>Audits</h4>
            </div>
        </div>
        <div class="card p-3 mt-2 bg-light">
            <table class="table" id="audits_table">
                <thead>
                    <tr>
                        <th>Table</th>
                        <th>field</th>
                        <th>Old Value</th>
                        <th>New Value</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        var table = $('#audits_table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('audits.list') }}",
            columns: [{
                    data: 'table'
                },
                {
                    data: 'field'
                },
                {
                    data: 'oldValue'
                },
                {
                    data: 'newValue'
                },
            ]
        });
    </script>
@endpush
