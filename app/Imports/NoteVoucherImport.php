<?php

namespace App\Imports;

use App\Models\NoteVoucher;
use App\Models\Product;
use App\Models\VoucherProduct;
use App\Models\VoucherProductDetail;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class NoteVoucherImport implements ToCollection, WithHeadingRow
{
    protected $noteVoucherData;
    protected $noteVoucher;

    public function __construct(array $noteVoucherData)
    {
        $this->noteVoucherData = $noteVoucherData;
    }

    public function collection(Collection $rows)
    {
        // Start database transaction
        \DB::beginTransaction();

        try {
            // Create the main note voucher
            $lastNoteVoucher = NoteVoucher::orderBy('id', 'desc')->first();
            $newNumber = $lastNoteVoucher ? $lastNoteVoucher->id + 1 : 1;

            $this->noteVoucher = NoteVoucher::create([
                'note_voucher_type_id' => $this->noteVoucherData['note_voucher_type_id'],
                'date_note_voucher' => $this->noteVoucherData['date_note_voucher'],
                'number' => $newNumber,
                'from_warehouse_id' => $this->noteVoucherData['fromWarehouse'],
                'to_warehouse_id' => $this->noteVoucherData['toWarehouse'] ?? null,
                'note' => $this->noteVoucherData['note'] ?? null,
            ]);

            // Process each row to create products
            foreach ($rows as $row) {
                // Skip empty rows
                if (empty($row['product_name_ar'])) {
                    continue;
                }

                // Find the product by Arabic name
                $product = Product::where('name_ar', $row['product_name_ar'])->first();

                if (!$product) {
                    continue; // Skip if product not found
                }

                // Create voucher product entry
                $voucherProduct = new VoucherProduct([
                    'note_voucher_id' => $this->noteVoucher->id,
                    'product_id' => $product->id,
                    'unit_id' => $row['unit_id'],
                    'quantity' => $row['quantity'],
                    'purchasing_price' => $row['purchasing_price'] ?? null,
                    'note' => $row['note'] ?? null,
                ]);

                $voucherProduct->save();

                // Add product details if they exist
                if (!empty($row['bin_number']) || !empty($row['serial_number']) || !empty($row['expiry_date'])) {
                    VoucherProductDetail::create([
                        'note_voucher_id' => $this->noteVoucher->id,
                        'voucher_product_id' => $voucherProduct->id,
                        'bin_number' => $row['bin_number'] ?? null,
                        'serial_number' => $row['serial_number'] ?? null,
                        'expiry_date' => $row['expiry_date'] ?? null,
                    ]);
                }
            }

            // Commit transaction
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }
    }

    public function getNoteVoucher()
    {
        return $this->noteVoucher;
    }
}
