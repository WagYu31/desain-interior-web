<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdersExport;

class ReportController extends Controller
{
    /**
     * Menampilkan halaman form untuk memilih laporan.
     */
    public function index()
    {
        return view('admin.reports.index');
    }

    /**
     * Memproses request dan men-generate laporan.
     */
    public function export(Request $request)
    {
        // 1. Validasi input dari form
        $request->validate([
            'report_type' => 'required|in:daily,monthly,yearly',
            'date_start' => 'required_if:report_type,daily|date|nullable',
            'month' => 'required_if:report_type,monthly|integer|between:1,12|nullable',
            'year' => 'required|integer|min:2020|max:'.date('Y'),
            'format' => 'required|in:pdf,excel',
        ]);

        $reportType = $request->input('report_type');
        $format = $request->input('format');
        $query = Order::with('user');
        $title = 'Laporan Pemesanan';
        $fileName = 'laporan-pemesanan';

        // 2. Filter data berdasarkan tipe laporan
        switch ($reportType) {
            case 'daily':
                $date = $request->input('date_start');
                $query->whereDate('order_date', $date);
                $title .= ' Harian (' . date('d F Y', strtotime($date)) . ')';
                $fileName .= '-harian-' . $date;
                break;
            case 'monthly':
                $month = $request->input('month');
                $year = $request->input('year');
                $query->whereMonth('order_date', $month)->whereYear('order_date', $year);
                $title .= ' Bulanan (' . date('F', mktime(0, 0, 0, $month, 1)) . ' ' . $year . ')';
                $fileName .= '-bulanan-' . $year . '-' . $month;
                break;
            case 'yearly':
                $year = $request->input('year');
                $query->whereYear('order_date', $year);
                $title .= ' Tahunan (' . $year . ')';
                $fileName .= '-tahunan-' . $year;
                break;
        }

        $orders = $query->latest()->get();

        if ($orders->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data pemesanan yang ditemukan untuk periode yang dipilih.');
        }

        // 3. Generate file berdasarkan format yang dipilih
        if ($format === 'pdf') {
            $pdf = Pdf::loadView('pdf.orders_report', compact('orders', 'title'));
            return $pdf->download($fileName . '.pdf');
        }

        if ($format === 'excel') {
            // Misal kamu pakai filter tanggal di laporan
            $startDate = $request->input('date_start');
            $endDate = $request->input('date_end'); // pastikan ini ada di form (bisa dikosongkan)
            $status = $request->input('status'); // opsional
        
            return Excel::download(
                new OrdersExport($startDate, $endDate, $status),
                $fileName . '.xlsx'
            );
        }        

        return redirect()->back()->with('error', 'Format laporan tidak valid.');
    }
}
