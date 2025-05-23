<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RajaOngkirController extends Controller
{
    public function getDomestic(Request $request)
    {
        $search = $request->query('search');

        $response = Http::withHeaders([
            'key' => env('RAJAONGKIR_API_KEY')
        ])->get(env('RAJAONGKIR_BASE_URL') . '/destination/domestic-destination', [
            'search' => $search
        ]);

        return response()->json($response->json());
    }

    public function getCost(Request $request)
    {
        $response = Http::asForm()->withHeaders([
            'key' => env('RAJAONGKIR_API_KEY'),
            'Content-Type' => 'application/x-www-form-urlencoded'
        ])->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
            'origin' => 31555,
            'destination' => $request->input('destination'),
            'weight' => $request->input('weight'),
            'courier' => $request->input('courier'),
            'price' => 'lowest',
        ]);

        return back()->with('result', $response->json());
    }
}
