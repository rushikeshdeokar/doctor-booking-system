<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['doctor', 'patient'])->get();

        return view('appointments.index', compact('appointments'));
    }
    
    public function getAppointmentsData()
    {
        $user = auth()->user();
        
        if ($user->role == 'doctor') {
            $appointments = Appointment::with(['doctor', 'patient'])
                ->where('doctor_id', $user->id)
                ->select('appointments.*');
        } elseif ($user->role == 'patient') {
            $appointments = Appointment::with(['doctor', 'patient'])
                ->where('patient_id', $user->id)
                ->select('appointments.*');
        } else {
            $appointments = collect();
        }

        return DataTables::of($appointments)
            ->addColumn('doctor_name', function ($appointment) {
                return $appointment->doctor ? $appointment->doctor->name : 'N/A';
            })
            ->addColumn('patient_name', function ($appointment) {
                return $appointment->patient ? $appointment->patient->name : 'N/A'; 
            })
            ->addColumn('action', function ($appointment) use ($user) { // Pass $user here
                $buttons = '';

                if ($user->role == 'patient') {
                    $buttons .= '<a href="' . route('appointments.edit', $appointment->id) . '" class="btn btn-sm btn-warning">Edit</a>';
                }

                if ($user->role == 'doctor') {
                    $buttons .= '
                        <button class="btn btn-sm btn-success approve-appointment-btn" data-id="' . $appointment->id . '">Approve</button>
                        <button class="btn btn-sm btn-warning postpone-btn" data-id="' . $appointment->id . '">Postpone</button>
                    ';
                }

                $buttons .= '
                    <button class="btn btn-sm btn-danger cancel-btn" data-id="' . $appointment->id . '">Cancel</button>
                    <button class="btn btn-sm btn-info reject-btn" data-id="' . $appointment->id . '">Reject</button>
                ';

                return $buttons;
            })
            ->make(true);
    }

    public function createAppointment()
    {
        $doctors = User::where('role', 'doctor')->get();
        return view('appointments.appointments', compact('doctors'));
    }

    public function storeAppointment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after:now',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 400); 
        }

        // Check for existing appointments at the same time for the same doctor
        $existingAppointment = Appointment::where('doctor_id', $request->doctor_id)
                                        ->where('appointment_date', $request->appointment_date)
                                        ->first();
        
        if ($existingAppointment) {
            return response()->json([
                'success' => false,
                'message' => 'The doctor is already booked at this time. Please choose another time.',
            ], 409); 
        }

        // Create the new appointment
        $appointment = Appointment::create([
            'doctor_id' => $request->doctor_id,
            'patient_id' => auth()->user()->id,
            'appointment_date' => $request->appointment_date,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Appointment booked successfully!',
            'appointment' => $appointment,
        ]);
    }

    public function edit($id)
    {
        $appointment = Appointment::with(['doctor', 'patient'])->findOrFail($id);
        $doctors = User::where('role', 'doctor')->get();

        return view('appointments.edit', compact('appointment', 'doctors'));
    }

    public function updateAppointmentStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after:now',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $appointment = Appointment::findOrFail($id);
        $appointment->update([
            'doctor_id' => $request->doctor_id,
            'appointment_date' => $request->appointment_date,
            'status' => 'pending',
        ]);

        return redirect()->route('appointments.index')->with('success', 'Appointment updated successfully!');
    }

    public function postponeAppointment(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        if (Auth::id() != $appointment->doctor_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $appointment->update(['status' => 'postponed']);

        return response()->json([
            'success' => true,
            'message' => 'Appointment postponed successfully!',
            'appointment' => $appointment,
        ]);
    }

    public function cancelAppointment(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $user = Auth::user();
    
        if ($user->id != $appointment->doctor_id && $user->id != $appointment->patient_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $appointment->update(['status' => 'canceled']);

        return response()->json([
            'success' => true,
            'message' => 'Appointment canceled successfully!',
            'appointment' => $appointment,
        ]);
    }

    public function rejectAppointment(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $user = Auth::user();
    
        if ($user->id != $appointment->doctor_id && $user->id != $appointment->patient_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    
        $appointment->update(['status' => 'rejected']);
    
        return response()->json([
            'success' => true,
            'message' => 'Appointment rejected successfully!',
            'appointment' => $appointment,
        ]);
    }
    

    public function approveAppointment(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        if (Auth::id() != $appointment->doctor_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $appointment->update(['status' => 'approved']);

        return response()->json([
            'success' => true,
            'message' => 'Appointment approved successfully!',
            'appointment' => $appointment,
        ]);
    }
}
