@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h2>Appointments List</h2>
        <div id="successMessage" class="alert alert-success d-none"></div>
        <div id="errorMessage" class="alert alert-danger d-none"></div>

        @if (auth()->user()->role == 'patient')
            <div class="mb-3">
                <a href="{{ route('create.appointments') }}" class="btn btn-primary">Book Appointment</a>
            </div>
        @endif

        <table id="appointments-table" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Doctor Name</th>
                    <th>Patient Name</th>
                    <th>Appointment Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            $('#appointments-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('appointments.data') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'doctor_name', name: 'doctor_name' },
                    { data: 'patient_name', name: 'patient_name' },
                    { data: 'appointment_date', name: 'appointment_date' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }  
                ]
            });

            $(document).on('click', '.postpone-btn', function() {
                const appointmentId = $(this).data('id');

                $.ajax({
                    url: "{{ route('appointments.postpone', '') }}/" + appointmentId,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: 'postponed'
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#appointments-table').DataTable().ajax.reload(); 
                        }
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON.message || 'An error occurred while postponing the appointment.');
                    }
                });
            });

            $(document).on('click', '.cancel-btn', function() {
                const appointmentId = $(this).data('id');

                $.ajax({
                    url: "{{ route('appointments.cancel', '') }}/" + appointmentId,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: 'canceled' 
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#appointments-table').DataTable().ajax.reload();
                        }
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON.message || 'An error occurred while canceling the appointment.');
                    }
                });
            });

            $(document).on('click', '.reject-btn', function() {
                const appointmentId = $(this).data('id');

                $.ajax({
                    url: "{{ route('appointments.reject', '') }}/" + appointmentId,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: 'rejected' 
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#appointments-table').DataTable().ajax.reload();
                        }
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON.message || 'An error occurred while rejecting the appointment.');
                    }
                });
            });

            $(document).on('click', '.approve-appointment-btn', function() {
                var appointmentId = $(this).data('id');

                $.ajax({
                    url: "{{ route('appointments.approve', '') }}/" + appointmentId,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#appointments-table').DataTable().ajax.reload(); 
                        } else {
                            alert(response.message); 
                        }
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors; 
                        var errorMessage = errors ? Object.values(errors).flat().join(', ') : 'An error occurred';
                        alert(errorMessage); 
                    }
                });
            });
        });
    </script>
    
@endsection
