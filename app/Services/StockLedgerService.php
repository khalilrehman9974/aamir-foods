<?php


namespace App\Services;

use App\Models\Area;
use App\Models\CoaDetailAccount;
use App\Stock;
use App\Models\StockLedger;
use Illuminate\Support\Facades\DB;
use App\Models\CoaInventoryDetailAccount;
use App\Models\DeliveredToParties;
use App\Models\SaleMan;
use App\Models\Sector;
use App\Models\Transporter;

class StockLedgerService
{
    public function prepareAndSaveData($request, $purchaseMasterInsert, $transactionType)
    {
        $debitQuantity = 0;
        $creditQuantity = 0;
        foreach ($request['product_id'] as $key => $value) {
            if (!empty($request['product_id'][$key])) {
                if ($transactionType == 'purchase' || $transactionType == 'sale_return') {
                    $debitQuantity = $request['quantity'][$key];
                }
                if ($transactionType == 'sale' || $transactionType == 'purchase_return') {
                    $creditQuantity = $request['quantity'][$key];
                }
                $data['invoice_id'] = $purchaseMasterInsert;
                $data['product_id'] = $request['product_id'][$key];
                $data['debit'] = $debitQuantity;
                $data['credit'] = $creditQuantity;
                $data['transaction_type'] = $transactionType;
                StockLedger::create($data);

            }
        }
    }

    public function DropDownData()
    {
        $result = [
            'products' => CoaInventoryDetailAccount::pluck('name', 'id'),
            'parties' => CoaDetailAccount::pluck('account_name', 'id'),
            'saleMans' => SaleMan::pluck('name', 'id'),
            'belts' => Sector::pluck('name', 'id'),
            'areas' => Area::pluck('name', 'id'),
            'deliveredToParties' => DeliveredToParties::pluck('party_name', 'id'),
            'transporters' => Transporter::pluck('name', 'id'),
        ];

        return $result;
    }

}
