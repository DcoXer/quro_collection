<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Picqer\Barcode\BarcodeGeneratorPNG;

class ResiController extends Controller
{
    public function download(Order $order): Response
    {
        $order->load(['items.product', 'user']);

        $sender = [
            'name'    => env('STORE_NAME', 'Quro Collection'),
            'phone'   => env('STORE_PHONE', '-'),
            'address' => env('STORE_ADDRESS', '-'),
            'city'    => env('STORE_CITY', '-'),
        ];

        $barcode = null;
        if ($order->tracking_number) {
            $generator = new BarcodeGeneratorPNG();
            $png = $generator->getBarcode($order->tracking_number, $generator::TYPE_CODE_128, 2, 60);
            $barcode = 'data:image/png;base64,' . base64_encode($png);
        }

        $pdf = Pdf::loadView('pdf.resi', compact('order', 'sender', 'barcode'))
            ->setPaper('a4', 'landscape')
            ->setOption('dpi', 150)
            ->setOption('isHtml5ParserEnabled', true);

        $filename = 'resi-' . $order->invoice_number . '.pdf';

        return $pdf->download($filename);
    }
}
