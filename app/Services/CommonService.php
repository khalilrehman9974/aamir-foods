<?php


namespace App\Services;

use App\Models\Area;
use App\Models\Bank;
use App\Models\Account;
use App\Models\Product;
use App\Models\SaleMan;
use App\Models\Sessions;
use App\Models\Attachments;
use App\Models\Distributer;
use App\Models\Transporter;
use App\Models\CoaDetailAccount;
use Illuminate\Support\Collection;
use App\Models\CoaInventorySubHead;
use App\Models\CoaInventoryMainHead;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use App\Models\CoaInventorySubSubHead;
use App\Models\CoaInventoryDetailAccount;
use Illuminate\Pagination\LengthAwarePaginator;

class CommonService
{
    const CASH_RECEIPT_VOUCHER = 'crv';
    const CASH_PAYMENT_VOUCHER = 'cpv';
    const BANK_PAYMENT_VOUCHER = 'bpv';
    const BANK_RECEIPT_VOUCHER = 'brv';
    const PER_PAGE = '10';
    const CURRENT_PAGE = '10';

    /**
     * create or Update content group
     * @param array $model
     * @param array $where
     * @param array $data
     * @return object $object.
     */
    public function findUpdateOrCreate($model, array $where, array $data)
    {
        // dd($model);
        $object = $model::firstOrNew($where);
        foreach ($data as $property => $value) {
            $object->{$property} = $value;
        }
        $object->save();

        return $object;
    }

    public function getAttachments($attachmentable_type, $attachmentable_id)
    {
        // return Attachments::where('attachmentable_type', $attachmentable_type)->where('attachmentable_id', $attachmentable_id)->get();
    }


    public function viewAttachment($fileName, $path, $redirectRoute)
    {
        $file = public_path($path . $fileName);
        if (file_exists($file) || !is_dir($fileName)) {
            $path = public_path($path . $fileName);
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename=' . $path);
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');

            readfile($path);
        } else {
            session()->flash('error', __('No file exists.'));
        }
    }

    /*
    * Pagination for list of packages.
    * @param: $data
    * @param: $perPage
    * @param: $page
    * @param: $options
    * */
    public function paginate($items, $perPage, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: Self::CURRENT_PAGE);
        $items = $items instanceof Collection ? $items : Collection::make($items)->sortByDesc('id');

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    /*
     * Get drop down data.
     * @return array $result.
     * */
    public function vouchersDropDownData()
    {
        $result = [
            'banks' => Bank::pluck('name', 'id'),
            // 'customers' => Customer::pluck('name','id'),
            // 'parties' => Party::pluck('name','id'),
            // 'accounts' => Account::pluck('name','account_code'),
            //'products' => Product::pluck('name','id'),
        ];

        return $result;
    }

    /*
     * Get Account type name.
     * @param: $type
     * */
    static function getAccountTypeName($type)
    {
        if ($type == 'P') {
            return 'Purchase';
        } elseif ($type == 'S') {
            return 'Sale';
        } elseif ($type == 'CH') {
            return 'Cash In Hand';
        }
    }



    public function getProductsList()
    {
        return Product::pluck('name', 'id');
    }


    public function deleteResource($modelClass)
    {
        $deleted = $modelClass::destroy(request()->id);
        if ($deleted) {
            $message = config('constants.delete');
            return response()->json(['status' => 'success', 'message' => $message]);
        } else {
            $message = config('constants.wrong');
            return response()->json(['status' => 'fail', 'message' => $message]);
        }
    }

    public function getInventorySubHeads($mainHead = null)
    {
        return CoaInventorySubHead::where('main_head', $mainHead)->pluck('name', 'id');
    }

    public function getInventorySubSubHeads($subHead = null)
    {
        return CoaInventorySubSubHead::where('sub_head', $subHead)->pluck('name', 'id');
    }

    public function getInventoryMainHeads()
    {
        return CoaInventoryMainHead::pluck('name', 'id');
    }

    public function uploadFile($request, $path, $fileName)
    {
        $request->file->move(public_path('uploads'), $fileName);
    }

    public function getSession()
    {
        return Sessions::where('user_id', Auth::Id())->select('business_id','financial_year')->first();
    }

    public function DropDownData()
    {
        $result = [
            'saleMans' => SaleMan::pluck('name','id'),
            'areas' => Area::pluck('name','id'),
            'parties' => CoaDetailAccount::pluck('account_name','id'),
            'transporters' => Transporter::pluck('name','id'),
            'products'=> CoaInventoryDetailAccount::pluck('name','code')
        ];

        return $result;
    }
}
