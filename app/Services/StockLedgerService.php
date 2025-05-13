<?php


namespace App\Services;

use App\Models\Area;
use App\Models\CoaDetailAccount;
use App\Models\StockLedger;
use App\Models\CoaInventoryDetailAccount;
use App\Models\CoaSubSubHead;
use App\Models\Country;
use App\Models\DeliveredToParties;
use App\Models\PriceTag;
use App\Models\SaleMan;
use App\Models\Sector;
use App\Models\Transporter;
use App\Models\Zone;

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
        $invArray = [4,6];
        $result = [
            'products' => CoaInventoryDetailAccount::pluck('name', 'id'),
            'parties' => CoaDetailAccount::pluck('account_name', 'id'),
            'saleMans' => SaleMan::pluck('name', 'id'),
            'belts' => Sector::pluck('name', 'id'),
            'areas' => Area::pluck('name', 'id'),
            'deliveredToParties' => DeliveredToParties::pluck('party_name', 'id'),
            'transporters' => Transporter::pluck('name', 'id'),
            'DeliveredToParties' => DeliveredToParties::pluck('party_name', 'id'),
            'countries' => Country::pluck('name','id'),
            'zones' => Zone::pluck('name','id'),
            'priceTags' => PriceTag::pluck('name', 'id'),
            'fourthHeads' => CoaSubSubHead::whereIn('main_head', $invArray)->pluck('account_name', 'id')
        ];

        return $result;
    }

}
