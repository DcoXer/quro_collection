<?php

namespace App\Http\Controllers;

use App\Services\OngkirService;
use App\Services\WilayahService;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    public function provinsi()
    {
        $wilayah = new WilayahService();
        return response()->json($wilayah->provinsi());
    }

    public function kabupaten(string $code)
    {
        $wilayah = new WilayahService();
        return response()->json($wilayah->kabupaten($code));
    }

    public function kecamatan(string $code)
    {
        $wilayah = new WilayahService();
        return response()->json($wilayah->kecamatan($code));
    }

    public function kelurahan(string $code)
    {
        $wilayah = new WilayahService();
        return response()->json($wilayah->kelurahan($code));
    }

    public function ongkir(Request $request)
    {
        $request->validate([
            'destination_village_code' => 'required|string',
            'weight'                   => 'required|numeric|min:0.1',
        ]);

        $ongkir = new OngkirService();
        $result = $ongkir->check(
            config('services.apicoiid.origin_village_code'),
            $request->destination_village_code,
            $request->weight,
        );

        // Store server-side so checkout cannot be tampered via DevTools
        if (!empty($result['data']['couriers'])) {
            session(['ongkir_verified' => [
                'destination' => $request->destination_village_code,
                'weight'      => (float) $request->weight,
                'couriers'    => $result['data']['couriers'],
            ]]);
        }

        return response()->json($result);
    }
}
