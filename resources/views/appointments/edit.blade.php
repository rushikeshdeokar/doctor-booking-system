@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h2>Edit Appointment</h2>

        <form action="{{ route('appointments.update', $appointment->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="doctor_id">Doctor</label>
                <select id="doctor_id" name="doctor_id" class="form-control" required>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}" {{ $doctor->id == $appointment->doctor_id ? 'selected' : '' }}>
                            {{ $doctor->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="appointment_date">Appointment Date</label>
                <input type="datetime-local" id="appointment_date" name="appointment_date" class="form-control" value="{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d\TH:i') }}" required>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Update Appointment</button>
                <a href="{{ route('appointments.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
