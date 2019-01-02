<?php
namespace App\Core;
use App\Models\Order;
//use Maatwebsite\Excel\Concerns\FromCollection;
//use Maatwebsite\Excel\Concerns\Exportable;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class InvoicesExport implements FromCollection, WithHeadings, ShouldAutoSize, WithColumnFormatting {

    use Exportable;

    public function collection()
    {
        $paymentsArray = [];

        Order::with(['job'])
            ->success()
            ->oldest()
            ->chunk(1000,function($orders) use (&$paymentsArray) {
                foreach($orders as $order) {
                    $columns = [
                        $order->id,
                        $order->package_type,
                        $order->scheduled_time,
                        $order->total .' KD',
                        optional(optional($order->address)->area)->name,
                        optional($order->address)->formatted_address,
                        optional(optional(optional($order->job)->driver)->user)->name,
                        $order->customer_name ? $order->customer_name : ($order->user ? $order->user->name : 'N/A') ,
                        $order->customer_mobile ? $order->customer_mobile : ($order->user ? $order->user->mobile : 'N/A') ,
                        strtoupper($order->payment_mode),
                        $order->invoice,
                        $order->transaction_id,
                        ucfirst(optional($order->job)->status),
                        $order->created_at->format('d-m-Y g:ia'),
                    ];
                    $paymentsArray[] = $columns;
                }

            })
        ;

        return collect($paymentsArray);
    }

    public function headings(): array
    {
//        $heading = ['Order ID', 'فئة', 'اسم المشروع', 'رقم اليتيم', 'اسم المتبرع', 'موبايل', 'مبلغ', 'الحالة', 'نوع الدفع', 'رقم الفاتورة','رقم المعاملة', 'تاريخ','وقت'];
        $heading = ['Order ID', 'Type', 'Time', 'Amount', 'Area', 'Address', 'Driver', 'Customer Name', 'Customer Mobile', 'Payment', 'Invoice ID', 'Transaction ID', 'Status', 'Date'];
        return $heading;
    }


    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
//            'G' => NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE,
        ];
    }
}