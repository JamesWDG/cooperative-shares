<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class InvoiceController extends Controller
{
    protected $viewFolderPath = 'screens.vendor.invoices.';

    /**
     * Vendor invoice list
     */
    public function index()
    {
        $vendor = Auth::user();

        $invoices = Invoice::where('vendor_id', $vendor->id)
            ->orderByDesc('issued_at')
            ->orderByDesc('id')
            ->latest()
            ->paginate(10);

        return view($this->viewFolderPath . 'list', [
            'invoices' => $invoices,
        ]);
    }

    /**
     * View single invoices
     */
    public function view(Invoice $invoice)
{
    $vendor = Auth::user();
    
    

    return view($this->viewFolderPath . 'view', [
        'invoice' => $invoice,
    ]);
}


    /**
     * Delete single invoice (AJAX)
     */
    public function destroy(Invoice $invoice)
    {
        $vendor = Auth::user();
        
        if ($invoice->vendor_id !== $vendor->id) {
            return response()->json([
                'status' => false,
                'msg'    => 'You are not allowed to delete this invoice.',
            ], 403);
        }

        try {
            $invoice->delete();

            return response()->json([
                'status' => true,
                'msg'    => 'Invoice deleted successfully.',
            ]);
        } catch (Exception $e) {
            Log::error('Vendor invoice delete failed', [
                'vendor_id'  => $vendor->id,
                'invoice_id' => $invoice->id,
                'error'      => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'msg'    => 'Failed to delete invoice. Please try again.',
            ], 500);
        }
    }

    /**
     * Delete multiple invoices (AJAX)
     */
    public function deleteMultiple(Request $request)
    {
        $vendor = Auth::user();

        $ids = $request->input('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'status' => false,
                'msg'    => 'No invoices selected.',
            ], 422);
        }

        try {
            $deleted = Invoice::where('vendor_id', $vendor->id)
                ->whereIn('id', $ids)
                ->delete();

            return response()->json([
                'status'  => true,
                'msg'     => 'Selected invoices deleted successfully.',
                'deleted' => $deleted,
            ]);
        } catch (Exception $e) {
            Log::error('Vendor multiple invoice delete failed', [
                'vendor_id' => $vendor->id,
                'ids'       => $ids,
                'error'     => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'msg'    => 'Failed to delete selected invoices. Please try again.',
            ], 500);
        }
    }
}
