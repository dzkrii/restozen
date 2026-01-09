<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Show payment form for an order.
     */
    public function create(Order $order)
    {
        $outlet = Auth::user()->current_outlet;
        $outletId = $outlet ? $outlet->id : null;

        if (!$outletId) abort(403);

        if ($order->outlet_id !== $outletId) {
            abort(403);
        }

        // Check if order is already paid
        if ($order->isPaid()) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Pesanan ini sudah dibayar.');
        }

        $order->load(['items', 'table', 'payments']);

        // Calculate remaining amount
        $paidAmount = $order->payments()->where('status', 'completed')->sum('amount');
        $remainingAmount = $order->total_amount - $paidAmount;

        return view('payments.create', compact('order', 'remainingAmount', 'paidAmount'));
    }

    /**
     * Process payment for an order.
     */
    public function store(Request $request, Order $order)
    {
        $outlet = Auth::user()->current_outlet;
        $outletId = $outlet ? $outlet->id : null;

        if (!$outletId) abort(403);

        if ($order->outlet_id !== $outletId) {
            abort(403);
        }

        $request->validate([
            'payment_method' => 'required|in:cash,card,qris,transfer,ewallet',
            'amount' => 'required|numeric|min:0',
            'cash_received' => 'nullable|numeric|min:0',
            'reference_number' => 'nullable|string|max:100',
        ]);

        // Calculate remaining
        $paidAmount = $order->payments()->where('status', 'completed')->sum('amount');
        $remainingAmount = $order->total_amount - $paidAmount;

        // Validate amount
        if ($request->amount > $remainingAmount) {
            return back()->with('error', 'Jumlah pembayaran melebihi sisa tagihan.');
        }

        try {
            DB::beginTransaction();

            $payment = Payment::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'payment_method' => $request->payment_method,
                'amount' => $request->amount,
                'reference_number' => $request->reference_number,
                'status' => Payment::STATUS_PENDING,
            ]);

            // Process based on payment method
            if ($request->payment_method === 'cash') {
                $cashReceived = $request->cash_received ?? $request->amount;
                $payment->processCashPayment($cashReceived);
            } else {
                // For non-cash payments, mark as completed immediately
                // In real implementation, this would integrate with payment gateway
                $payment->markComplete();
            }

            // Check if order is fully paid
            $order->refresh();
            if ($order->isPaid()) {
                $order->update(['status' => Order::STATUS_COMPLETED, 'completed_at' => now()]);

                // Mark all items as served (completed)
                $order->items()->whereNotIn('status', ['served', 'cancelled'])->update([
                    'status' => 'served'
                ]);

                // Release table
                if ($order->table) {
                    $order->table->release();
                }
            }

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Pembayaran berhasil diproses!')
                ->with('payment_success', true);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }
}
