@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">Book an Appointment</h1>
        <div id="errorMessage" class="alert alert-danger d-none"></div>

        <form id="appointmentForm" class="row g-3">
            <div class="col-md-6">
                <label for="doctor_id" class="form-label">Select Doctor</label>
                <select id="doctor_id" name="doctor_id" class="form-select">
                    @foreach ($doctors as $doctor)
                        <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label for="appointment_date" class="form-label">Appointment Date</label>
                <input type="datetime-local" id="appointment_date" name="appointment_date" class="form-control" required>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">Book Appointment</button>
            </div>
        </form>
    </div>

    <script>
        $('#appointmentForm').on('submit', function(e) {
            e.preventDefault();
            
            var doctorId = $('#doctor_id').val();
            var appointmentDate = $('#appointment_date').val();

            $.ajax({
                url: '/store/appointments',
                method: 'POST',
                data: {
                    doctor_id: doctorId,
                    appointment_date: appointmentDate,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                if (response.success) {
                    $('#successMessage').removeClass('d-none').text(response.message);
                    window.location.href = "{{ route('appointments.index') }}";
                }
                },
                error: function(xhr) {
                    let errorMessage = '';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        for (let key in xhr.responseJSON.errors) {
                            errorMessage += xhr.responseJSON.errors[key].join(', ') + '<br>';
                        }
                    } else {
                        errorMessage += 'The doctor is already booked at this time. Please choose another time.';
                    }

                    $('#errorMessage').html(errorMessage).removeClass('d-none');
                }

            });
        });
    </script>
@endsection
