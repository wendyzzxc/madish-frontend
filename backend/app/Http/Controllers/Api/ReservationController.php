<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{

    public function index()
    {
        $id_user = Auth::user()->id;
        $reservations = Reservation::all();

        if (count($reservations) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $reservations,
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id)
    {
        $id_user = Auth::user()->id;
        $reservations = Reservation::find($id)->where('id_customer', '=', $id_user);

        if (!is_null($reservations)) {
            return response([
                'message' => 'Retrieve Reservation Success',
                'data' => $reservations
            ], 200);
        }

        return response([
            'message' => 'Reservation Not Found',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'name' => 'required|max:60|regex:/^[a-zA-Z]+$/|alpha',
            'phone_num' => 'required|numeric|regex:/(08)[0-9]{8,11}/',
            'booking_date' => 'required|date_format:Y-m-d',
            'booking_time' => 'required',
            'num_customer' => 'required|min:2|numeric',
            'room' => 'required'
        ]);


        $storeData['id_customer'] = Auth::user()->id;
        $storeData['id_reservation'] = random_int(100000, 999999);
        $storeData['status'] = 'Wait';

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $reservations = Reservation::create($storeData);
        return response([
            'message' => 'Add Reservation Success',
            'data' => $reservations
        ], 200);
    }

    public function destroy($id)
    {
        $reservation = Reservation::find($id);

        if (is_null($reservation)) {
            return response([
                'message' => 'Reservation Not Found',
                'data' => null
            ], 404);
        }

        if ($reservation->delete()) {
            return response([
                'message' => 'Delete Reservation Success',
                'data' => $reservation
            ], 200);
        }

        return response([
            'message' => 'Delete Reservation Failed',
            'data' => null,
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $id_user = Auth::user()->id;
        $reservation = Reservation::find($id);
        if (is_null($reservation)) {
            return response([
                'message' => 'Reservation Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        if ($id_user != '1') {

            $validate = Validator::make($updateData, [
                'name' => 'required|max:60|regex:/^[a-zA-Z]+$/|alpha',
                'phone_num' => 'required|numeric|regex:/(08)[0-9]{8,11}/',
                'booking_date' => 'required|date_format:Y-m-d',
                'booking_time' => 'required',
                'num_customer' => 'required|numeric|min:2',
                'room' => 'required',
                'status' => 'required'
            ]);

            if ($validate->fails())
                return response(['message' => $validate->errors()], 400);

            $reservation->name = $updateData['name'];
            $reservation->phone_num = $updateData['phone_num'];
            $reservation->booking_date = $updateData['booking_date'];
            $reservation->booking_date = $updateData['booking_time'];
            $reservation->num_customer = $updateData['num_customer'];
            $reservation->status = $updateData['status'];

            if ($reservation->save()) {
                return response([
                    'message' => 'Update Reservation Success',
                    'data' => $reservation
                ], 200);
            }
        } else {
            $validate = Validator::make($updateData, [
                'num_table' => 'required',
                'status' => 'required'
            ]);

            if ($validate->fails())
                return response(['message' => $validate->errors()], 400);

            $reservation->table_num = $updateData['num_table'];
            $reservation->status = $updateData['status'];

            if ($reservation->save()) {
                return response([
                    'message' => 'Update Reservation Success',
                    'data' => $reservation
                ], 200);
            }
        }
        return response([
            'message' => 'Update Reservation Failed',
            'data' => null,
        ], 400);
    }
}
