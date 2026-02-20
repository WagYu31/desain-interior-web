<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h3 { margin: 0; }
        .header p { margin: 0; font-size: 10px; color: #666; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <h3>{{ config('app.name') }}</h3>
        <p>{{ $title }}</p>
        <p>Periode: {{ request()->get('start_date') }} s/d {{ request()->get('end_date') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Kontak</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Tipe Proyek</th>
                <th>Harga Final</th>
            </tr>
        </thead>
        <tbody>
            @php $totalRevenue = 0; @endphp
            @forelse($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->contact_name }}</td>
                    <td>{{ $order->order_date ? $order->order_date->format('d-m-Y') : 'N/A' }}</td>
                    <td>{{ Str::title(str_replace('_', ' ', $order->status)) }}</td>
                    <td>
                        {{ $order->property_type }} - 
                        @if(is_array($order->design_type))
                            {{ implode(', ', $order->design_type) }}
                        @else
                            {{ $order->design_type }}
                        @endif
                    </td>
                    <td style="text-align: right;">
                        @if($order->final_price > 0)
                            Rp {{ number_format($order->final_price, 0, ',', '.') }}
                            @php $totalRevenue += $order->final_price; @endphp
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data pesanan pada periode yang dipilih.</td>
                </tr>
            @endforelse
            {{-- Baris untuk Total Pendapatan --}}
            @if($orders->isNotEmpty())
            <tr>
                <td colspan="5" style="text-align: right; font-weight: bold;">TOTAL PENDAPATAN (Completed)</td>
                <td style="text-align: right; font-weight: bold;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
            </tr>
            @endif
        </tbody>
    </table>
</body>
</html>