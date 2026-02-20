<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Carbon\Carbon;

class OrdersExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $startDate;
    protected $endDate;
    protected $status;

    public function __construct($startDate, $endDate, $status)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
    }

    public function query()
    {
        $query = Order::query();

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('order_date', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ]);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query;
    }

    /**
     * Menentukan header kolom di file Excel.
     */
    public function headings(): array
    {
        return [
            '#',
            'Tanggal Pesan',
            'Nama Kontak',
            'Email Kontak',
            'No. Telepon',
            'Tipe Klien',
            'Tipe Properti',
            'Tipe Desain',
            'Status',
            'Harga Final (Rp)', // <-- Header Baru
        ];
    }

    /**
     * Memetakan data dari setiap baris database ke baris Excel.
     * Ini sangat penting untuk menangani array (design_type) dan format harga.
     */
    public function map($order): array
    {
        return [
            $order->id,
            $order->order_date ? $order->order_date->format('d-m-Y') : '',
            $order->contact_name,
            $order->contact_email,
            // Nomor telepon dikonversi ke string agar tidak jadi notasi ilmiah
            "" . $order->contact_phone,
            $order->client_type,
            $order->property_type,
            is_array($order->design_type) ? implode(', ', $order->design_type) : $order->design_type,
            ucfirst(str_replace('_', ' ', $order->status)),
            // Format harga dengan pemisah ribuan dan tanpa notasi ilmiah
            $order->final_price ? number_format($order->final_price, 0, ',', '.') : '0',
        ];
    }

}