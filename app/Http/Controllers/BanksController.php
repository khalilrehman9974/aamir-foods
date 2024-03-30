<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Http\Requests\BankStoreRequest;
use App\Services\BankService;
use App\Services\PermissionService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BanksController extends Controller
{
    private $bankService;
    private $permissionService;
    //
    public function __construct(BankService $bankService, PermissionService $permissionService)
    {
        $this->bankService = $bankService;
        $this->permissionService = $permissionService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banks = Bank::paginate(Bank::PER_PAGE);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '11');
        return view('banks.index', compact('banks', 'permission'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '11');
        return view('banks.create', compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  BankStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BankStoreRequest $request)
    {
        $data = $request->except('_token','bankId');
        $this->bankService->findUpdateOrCreate(Bank::class, ['id'=>!empty(request('bankId')) ? request('bankId') : null], $data);
        $message = 'Bank has been added.';
        if(request('bankId')){
            $message = 'Bank info has been updated';
        }
        session()->flash('message', $message);
        return redirect('bank/list');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bank = Bank::find($id);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '11');

        return view('banks.create', compact('bank', 'permission'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $deleted = Bank::destroy(request()->id);
        if ($deleted) {
            return response()->json(['status' => 'success', 'message' => 'Bank is deleted']);
        } else {
            return response()->json(['status' => 'fail', 'message' => 'Something is went wrong']);
        }
    }

    /**
     * Search record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response.
     */
    public function search(Request $request)
    {
        $params = request()->all();
        $banks = $this->bankService->search(request()->all());

        return view('banks.index', compact('banks', 'params'));
    }
}
