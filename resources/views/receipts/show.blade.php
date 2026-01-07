<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk {{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            background: #fff;
        }

        .receipt {
            width: 302px; /* 80mm thermal printer */
            max-width: 100%;
            margin: 0 auto;
            padding: 10px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #000;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .header p {
            font-size: 11px;
        }

        .info {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #000;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }

        .info-row .label {
            font-weight: normal;
        }

        .info-row .value {
            text-align: right;
        }

        .items {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #000;
        }

        .item {
            margin-bottom: 5px;
        }

        .item-name {
            font-weight: bold;
        }

        .item-details {
            display: flex;
            justify-content: space-between;
            padding-left: 10px;
            font-size: 11px;
        }

        .totals {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #000;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .total-row.grand {
            font-size: 14px;
            font-weight: bold;
            padding-top: 5px;
            border-top: 1px solid #000;
            margin-top: 5px;
        }

        .payment {
            margin-bottom: 15px;
        }

        .payment-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }

        .footer {
            text-align: center;
            padding-top: 10px;
            border-top: 1px dashed #000;
        }

        .footer p {
            font-size: 11px;
            margin-bottom: 3px;
        }

        .footer .thank-you {
            font-weight: bold;
            font-size: 14px;
            margin-top: 10px;
        }

        @media print {
            body {
                width: 80mm;
            }
            .receipt {
                width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }

        .print-btn {
            display: block;
            width: 100%;
            max-width: 302px;
            margin: 20px auto;
            padding: 12px;
            background: #16a34a;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
        }

        .print-btn:hover {
            background: #15803d;
        }
    </style>
</head>
<body>
    <button class="print-btn no-print" onclick="window.print()">
        🖨️ Cetak Struk
    </button>

    <div class="receipt">
        {{-- Header --}}
        <div class="header">
            <h1>{{ $order->outlet->name ?? 'RESTOZEN' }}</h1>
            <p>{{ $order->outlet->address ?? '' }}</p>
            @if($order->outlet->phone)
                <p>Telp: {{ $order->outlet->phone }}</p>
            @endif
        </div>

        {{-- Order Info --}}
        <div class="info">
            <div class="info-row">
                <span class="label">No. Order:</span>
                <span class="value">{{ $order->order_number }}</span>
            </div>
            <div class="info-row">
                <span class="label">Tanggal:</span>
                <span class="value">{{ $order->created_at->format('d/m/Y H:i') }}</span>
            </div>
            @if($order->table)
                <div class="info-row">
                    <span class="label">Meja:</span>
                    <span class="value">{{ $order->table->number }}</span>
                </div>
            @endif
            <div class="info-row">
                <span class="label">Tipe:</span>
                <span class="value">{{ ucfirst(str_replace('_', ' ', $order->order_type)) }}</span>
            </div>
            @if($order->customer_name)
                <div class="info-row">
                    <span class="label">Pelanggan:</span>
                    <span class="value">{{ $order->customer_name }}</span>
                </div>
            @endif
            <div class="info-row">
                <span class="label">Kasir:</span>
                <span class="value">{{ $order->user->name ?? '-' }}</span>
            </div>
        </div>

        {{-- Items --}}
        <div class="items">
            @foreach($order->items as $item)
                <div class="item">
                    <div class="item-name">{{ $item->menu_item_name }}</div>
                    <div class="item-details">
                        <span>{{ $item->quantity }} x {{ number_format($item->unit_price, 0, ',', '.') }}</span>
                        <span>{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if($item->notes)
                        <div class="item-details" style="font-style: italic; color: #666;">
                            <span>{{ $item->notes }}</span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Totals --}}
        <div class="totals">
            <div class="total-row">
                <span>Subtotal</span>
                <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($order->tax_amount > 0)
                <div class="total-row">
                    <span>Pajak</span>
                    <span>Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</span>
                </div>
            @endif
            @if($order->service_charge > 0)
                <div class="total-row">
                    <span>Service</span>
                    <span>Rp {{ number_format($order->service_charge, 0, ',', '.') }}</span>
                </div>
            @endif
            @if($order->discount_amount > 0)
                <div class="total-row">
                    <span>Diskon</span>
                    <span>-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="total-row grand">
                <span>TOTAL</span>
                <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Payment --}}
        @if($order->payments->isNotEmpty())
            <div class="payment">
                @foreach($order->payments->where('status', 'completed') as $payment)
                    <div class="payment-row">
                        <span>{{ ucfirst($payment->payment_method) }}</span>
                        <span>Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                    </div>
                    @if($payment->cash_received && $payment->payment_method === 'cash')
                        <div class="payment-row">
                            <span>Tunai</span>
                            <span>Rp {{ number_format($payment->cash_received, 0, ',', '.') }}</span>
                        </div>
                        <div class="payment-row">
                            <span>Kembali</span>
                            <span>Rp {{ number_format($payment->change_amount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

        {{-- Footer --}}
        <div class="footer">
            <p>{{ $order->created_at->format('d/m/Y H:i:s') }}</p>
            <p class="thank-you">Terima Kasih!</p>
            <p>Silakan datang kembali</p>
        </div>
    </div>

    <button class="print-btn no-print" onclick="window.print()">
        🖨️ Cetak Struk
    </button>
</body>
</html>
