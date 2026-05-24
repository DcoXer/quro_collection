<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $cart  = session()->get('cart', []);
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        $voucher = Voucher::where('code', strtoupper($request->code))
            ->first();

        if (!$voucher) {
            return response()->json(['success' => false, 'message' => 'Voucher tidak ditemukan.']);
        }

        $validation = $voucher->isValid($total, auth()->id());

        if (!$validation['valid']) {
            return response()->json(['success' => false, 'message' => $validation['message']]);
        }

        $discount = $voucher->calculateDiscount($total);

        session()->put('voucher', [
            'code'     => $voucher->code,
            'discount' => $discount,
            'type'     => $voucher->type,
            'value'    => $voucher->value,
        ]);

        return response()->json([
            'success'            => true,
            'message'            => 'Voucher berhasil diterapkan!',
            'code'               => $voucher->code,
            'discount'           => $discount,
            'discount_formatted' => 'Rp ' . number_format($discount, 0, ',', '.'),
            'final_total'        => $total - $discount,
            'final_formatted'    => 'Rp ' . number_format($total - $discount, 0, ',', '.'),
        ]);
    }

    public function remove()
    {
        session()->forget('voucher');
        return response()->json(['success' => true]);
    }
}