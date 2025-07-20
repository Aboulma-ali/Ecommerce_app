<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function download($orderId)
    {
        $order = Order::with(['user', 'shippingAddress', 'orderItems.product'])->findOrFail($orderId);

        $pdf = Pdf::loadView('pdf.invoice', [
            'order' => $order,
        ]);

        return $pdf->download('facture-' . $order->id . '.pdf');
    }
}
