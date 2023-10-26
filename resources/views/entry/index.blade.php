@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card p-3 bg-light">
            <div class="d-flex align-items-center">
                <h4>Entries</h4>
                <!-- Button Add Entry modal -->
                <button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#addEntryModal">
                    Add
                </button>
            </div>
        </div>
        <div class="card p-3 mt-2 bg-light">
            <table class="table" id="entries_table">
                <thead>
                    <tr>
                        <th>Account</th>
                        <th>Narration</th>
                        <th>Currency</th>
                        <th>Credit</th>
                        <th>Debit</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Add Entry Modal -->
    <div class="modal fade" id="addEntryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Entry</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-entries-store" action="{{ route('entries.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="account">Account</label>
                            <input type="text" class="form-control" name="account" aria-describedby="emailHelp">
                        </div>
                        <div class="form-group">
                            <label for="narration">Narration</label>
                            <input type="text" class="form-control" name="narration">
                        </div>
                        <div class="form-group">
                            <label for="currency">Currency</label>
                            <input type="text" class="form-control" name="currency">
                        </div>
                        <div class="form-group">
                            <label for="credit">Credit</label>
                            <input type="text" class="form-control" name="credit">
                        </div>
                        <div class="form-group">
                            <label for="debit">Debit</label>
                            <input type="text" class="form-control" name="debit">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary"
                        onclick="$(this).closest('.modal').find('form').trigger('submit');">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Entry Modal -->
    <div class="modal fade" id="editEntryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Entry</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-entries-update" action="{{ route('entries.update', ':id') }}" method="PUT">
                        @csrf
                        <input type="hidden" name="id">
                        <div class="form-group">
                            <label for="account">Account</label>
                            <input type="text" class="form-control" name="account" aria-describedby="emailHelp">
                        </div>
                        <div class="form-group">
                            <label for="narration">Narration</label>
                            <input type="text" class="form-control" name="narration">
                        </div>
                        <div class="form-group">
                            <label for="currency">Currency</label>
                            <input type="text" class="form-control" name="currency">
                        </div>
                        <div class="form-group">
                            <label for="credit">Credit</label>
                            <input type="text" class="form-control" name="credit">
                        </div>
                        <div class="form-group">
                            <label for="debit">Debit</label>
                            <input type="text" class="form-control" name="debit">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary"
                        onclick="$(this).closest('.modal').find('form').trigger('submit');">Submit</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        var table = $('#entries_table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('entries.list') }}",
            columns: [{
                    data: 'account'
                },
                {
                    data: 'narration'
                },
                {
                    data: 'currency'
                },
                {
                    data: 'credit'
                },
                {
                    data: 'debit'
                },
                {
                    render: function(_, b, data) {
                        console.log(data);
                        resp = `<div class='d-flex' style='gap: 5px;'>`;
                        resp +=
                            `<button class='btn btn-primary' onclick='editData(${data.id})'>Edit</button>`;
                        resp +=
                            `<button class='btn btn-danger' onclick='deleteData(${data.id})'>Delete</button>`;
                        resp += `</div>`;
                        return resp;
                    }
                },
            ]
        });

        $('#form-entries-store').on('submit', function(e) {
            e.preventDefault();
            const url = $(this).attr('action');
            const method = $(this).attr('method');
            const formDataArray = $(this).serializeArray();
            formData = {};

            formDataArray.forEach(item => {
                formData[item.name] = item.value;
            });
            $.ajax({
                url,
                type: method,
                data: formData,
                success: function(response) {
                    console.log(response);
                    $('#addEntryModal').modal('hide');
                    table.draw();
                },
                error: function(xhr, status, error) {
                    console.error(xhr, status, error);
                }
            });
        });
        $('#form-entries-update').on('submit', function(e) {
            e.preventDefault();
            const url = $(this).attr('action');
            const method = $(this).attr('method');
            const formDataArray = $(this).serializeArray();
            formData = {};

            formDataArray.forEach(item => {
                formData[item.name] = item.value;
            });
            id =  $('#editEntryModal input[name="id"]').val();
            $.ajax({
                url: url.replaceAll(':id', id),
                type: method,
                data: formData,
                success: function(response) {
                    console.log(response);
                    $('#editEntryModal').modal('hide');
                    table.draw();
                },
                error: function(xhr, status, error) {
                    console.error(xhr, status, error);
                }
            });
        });

        function editData(id) {
            url = `{{ route('entries.show', ['entry' => ':id']) }}`;
            $.ajax({
                url: url.replaceAll(':id', id),
                type: 'GET',
                success: function(response) {
                    $('#editEntryModal input[name="account"]').val(response.account);
                    $('#editEntryModal input[name="narration"]').val(response.narration);
                    $('#editEntryModal input[name="currency"]').val(response.currency);
                    $('#editEntryModal input[name="credit"]').val(response.credit);
                    $('#editEntryModal input[name="debit"]').val(response.debit);
                    $('#editEntryModal input[name="id"]').val(response.id);
                },
                error: function(xhr, status, error) {
                    console.error(xhr, status, error);
                }
            });
            $('#editEntryModal').modal('show');
        }

        function deleteData(id) {
            const url = `{{ route('entries.destroy', ['entry' => ':id']) }}`;
            $.ajax({
                url: url.replaceAll(':id', id),
                type: 'DELETE',
                data: {
                    '_token': '{{ csrf_token() }}'
                },
                success: function(response) {
                    table.draw();
                },
                error: function(xhr, status, error) {
                    console.error(xhr, status, error);
                }
            });
        }
    </script>
@endpush
