<?php

namespace App\Http\Controllers\Sales;

use App\DailySale;
use App\Exports\AllocationCustomerExport;
use App\Exports\AllocationProductExport;
use App\Http\Controllers\Controller;
use App\Repositories\Sales\AllocationRepository;
use Maatwebsite\Excel\Facades\Excel;
use PDF;


class AllocationExportController extends Controller
{
    /**
     * @var AllocationRepository
     */
    protected $allocation;

    /**
     * AllocationController constructor.
     * @param AllocationRepository $allocation
     */
    public function __construct(AllocationRepository $allocation)
    {
        $this->allocation = $allocation;
    }

    /**
     * @param DailySale $allocation
     * @return mixed
     */
    public function exportCustomer(DailySale $allocation)
    {
        if (request()->input()) {
            return $this->exportCustomerExcel($allocation);
        }
        $data = [];
        $data['customers'] = $allocation->customers;
        $data['allocation'] = $allocation;
        $pdf = PDF::loadView('sales.allocation.export.customer', $data);
        return $pdf->download('Sales Allocation - Customers (' . $allocation->code . ')' . '.pdf');
    }

    /**
     * @param DailySale $allocation
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportCustomerExcel(DailySale $allocation)
    {
        return Excel::download(new AllocationCustomerExport($allocation), 'Sales Allocation - Customers (' . $allocation->code . ')' . '.xlsx', 'Xlsx');
    }

    /**
     * @param DailySale $allocation
     * @return mixed
     */
    public function exportProducts(DailySale $allocation)
    {
        if (request()->input()) {
            return $this->exportProductExcel($allocation);
        }
        $data = [];
        $data['products'] = $allocation->items;
        $data['allocation'] = $allocation;
        $pdf = PDF::loadView('sales.allocation.export.product', $data);
        return $pdf->download('Sales Allocation - Products (' . $allocation->code . ')' . '.pdf');
    }

    public function exportProductExcel(DailySale $allocation)
    {
        return Excel::download(new AllocationProductExport($allocation), 'Sales Allocation - Customers (' . $allocation->code . ')' . '.xlsx', 'Xlsx');
    }

    /**
     * @param DailySale $allocation
     * @return mixed
     */
    public function exportProductHistory(DailySale $allocation)
    {
        $data = [];
        $stockHistories = $allocation->stockHistories;
        $salesHandover = $allocation->salesHandover;
        $handOverStock = collect();
        if ($salesHandover) {
            $handOverStock = $salesHandover->stockHistories;
        }
        $productHistories = $stockHistories->merge($handOverStock)->sortByDesc('id')->load(['transable', 'stock.product'])->groupBy('stock.product.id');
        $data['histories'] = $productHistories;
        $data['allocation'] = $allocation;
        $pdf = PDF::loadView('sales.allocation.export.product-history', $data);
        return $pdf->download('Sales Allocation - Stock Histories (' . $allocation->code . ')' . '.pdf');
    }

    public function creditOrderExport(DailySale $allocation)
    {
        $data = [];
        $orders = $allocation->dailySaleCreditOrders()->with('order.payments')->get();
        $data['orders'] = $orders;
        $data['allocation'] = $allocation;
        $data['order_total'] = $orders->pluck('order')->whereIn('status', ['Open', 'Closed'])->sum('total');
        $data['payment_total'] = $orders->pluck('order')->pluck('payments')->collapse()->where('status', 'Paid')->sum('payment');
        $pdf = PDF::loadView('sales.allocation.export.credit-orders', $data);
        return $pdf->download('Sales Allocation - Credit Orders (' . $allocation->code . ')' . '.pdf');
    }
}
