<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sale Invoice Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .label {
            width: 100px;
            display: inline-block;
            font-weight: bold;
            font-size: 14px;
        }

        .value {
            width: 250px;
            display: inline-block;
        }

        .mono {
            font-family: Calibri, sans-serif;
        }

        .order-block {
            break-inside: avoid;
            page-break-inside: avoid;
            border: 2px solid black;
            padding: 1rem;
            margin-bottom: 1.5rem;
            background-color: #f3f4f6;
            border-radius: 0.25rem;
        }

        table,
        th,
        td {
            border: 1px solid black !important;
            border-collapse: collapse;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
                font-size: 10px;
            }

            .container {
                width: 100%;
                margin: 0;
                padding: 0;
            }

            @page {
                size: A4 portrait;
                margin: 2mm !important;
            }

            h1 {
                font-size: 18px;
            }

            h2 {
                font-size: 16px;
            }

            h3 {
                font-size: 14px;
            }

            td,
            th {
                font-size: 12px;
            }

            p {
                font-size: 10px;
            }

            .order-block {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body class="bg-white text-black p-0 text-sm mono">
    <div class="container w-full mx-auto">
        @foreach ($orders as $order)
            <div class="order-block">
                <!-- Header Info -->
                <div class="mb-1">
                    <span class="label">From Date</span>: <span class="value">{{ $fromDate }}</span>
                    <span class="label">To Date</span>: {{ $toDate }}
                </div>
                <div class="mb-2">
                    <span class="label">Invoice No</span>: <span class="value">{{ $order->id ?? '' }}</span>
                    <span class="label">Invoice Date</span>: {{ $order->date ?? '' }}
                </div>
                <div class="mb-1">
                    <span class="label">Party</span>: <span class="value">{{ $dropDownData['parties'][$order->party_id] ?? '' }}</span>
                    <span class="label">Sector</span>: {{ $dropDownData['belts'][$order->sector] ?? '' }}
                </div>
                <div class="mb-2">
                    <span class="label">Sale Man</span>: <span class="value">{{ $dropDownData['saleMans'][$order->saleman] ?? '' }}</span>
                    <span class="label">Area</span>: {{ $dropDownData['areas'][$order->area] ?? '' }}
                </div>
                <div class="mb-2">
                    <span class="label">SO #</span>: <span class="value">{{ $order->sale_order_number ?? '' }}</span>
                    <span class="label">Order Date</span>: {{ $order->sale_order_master->date ?? '' }}
                </div>
                <div class="mb-2">
                    <span class="label">Order Status</span>: <span class="value">{{ $order->sale_order_master->status ?? '' }}</span>
                    <span class="label">Dispatch Date</span>: {{ $order->dispatch_note_master->date }}
                </div>
                <div class="mb-2">
                    <span class="label">Transporter</span>: <span class="value">{{ $dropDownData['transporters'][$order->dispatch_note_master->transporter_id] ?? '' }}</span>
                    <span class="label">Dispatch No</span>: {{ $order->dispatch_note_number ?? '' }}
                </div>
                <div class="mb-2">
                    <span class="label">Delivered To</span>: <span class="value">{{ $dropDownData['deliveredToParties'][$order->delivered_to] ?? 'Same From Party' }}</span>
                    <span class="label">Bility No</span>: {{ $order->dispatch_note_master->bility_no ?? '' }}
                </div>

                <!-- Product Table -->
                <table class="w-full text-left mb-4 text-sm">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-2 py-1 w-[3%]">Sr #</th>
                            <th class="px-2 py-1 w-[17%]">Product</th>
                            <th class="px-2 py-1 w-[5%]">P/T</th>
                            <th class="px-2 py-1 w-[8%]">SO/QTY</th>
                            <th class="px-2 py-1 w-[8%]">Disp/Qty</th>
                            <th class="px-2 py-1 w-[8%]">T.Dzns</th>
                            <th class="px-2 py-1 w-[8%]">Rate</th>
                            <th class="px-2 py-1 w-[8%]">Discount</th>
                            <th class="px-2 py-1 w-[8%]">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $serial = 1; @endphp
                        @foreach ($order->details as $detail)
                            <tr>
                                <td class="px-2 py-1 font-bold">{{ $serial++ }}</td>
                                <td class="px-2 py-1">{{ $dropDownData['products'][$detail->product_id] ?? 'Unknown' }}</td>
                                <td class="px-2 py-1">{{ $detail->packing_type }}</td>
                                <td class="px-2 py-1">{{ $detail->soQuantity }}</td>
                                <td class="px-2 py-1">{{ $detail->dispQuantity }}</td>
                                <td class="px-2 py-1">{{ $detail->total_dzns ?? 0 }}</td>
                                <td class="px-2 py-1">{{ $detail->rate ?? 0 }}</td>
                                <td class="px-2 py-1">{{ $detail->discount ?? 0 }}</td>
                                <td class="px-2 py-1">{{ $detail->amount ?? 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Two-column layout -->
                <div class="flex gap-4">
                    <!-- Packing Totals -->
                    <div class="w-4/5">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="" style="width: 19% !important">Total</th>
                                    <th style="width: 5% !important">Detail</th>
                                    <th style="width: 15% !important">Ord/Qty</th>
                                    <th style="width: 13.5% !important">Disp/Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td>Boray</td>
                                    <td>{{ $order->boray_so_quantity }}</td>
                                    <td>{{ $order->boray_disp_quantity}}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Carton</td>
                                    <td>{{ $order->carton_so_quantity}}</td>
                                    <td>{{ $order->carton_disp_quantity }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Billing Summary -->
                    <div class="w-1/4">
                        <table class="w-full text-sm">
                            <tbody>
                                <tr>
                                    <td><b>Gross Amount</b></td>
                                    <td>{{ $order->gross_bill ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><b>Fair</b></td>
                                    <td>{{ $order->carriage ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Commission</b>
                                        <span>/ {{ $order->party->commision ?? '-' }}%</span>
                                    </td>
                                    <td>{{ $order->commission_amount ?? '0.00' }}</td>
                                </tr>
                                <tr>
                                    <td><b>Net Amount</b></td>
                                    <td>{{ $order->net_amount ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Grand Totals -->
        {{-- <h3 class="text-lg font-bold mt-4">Grand Totals by Packing Type</h3> --}}
        <table class="table-auto w-full border mt-2 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-2 py-1">Packing Type</th>
                    <th class="border px-2 py-1">Total SO Quantity</th>
                    <th class="border px-2 py-1">Total Quantity</th>
                    <th class="border px-2 py-1">Balance</th>
                </tr>
            </thead>
            <tbody>
                {{-- Example static row (uncomment and replace with loop if needed) --}}
                {{-- @foreach (['Boray', 'Carton'] as $type)
                    <tr>
                        <td class="border px-2 py-1">{{ $type }}</td>
                        <td class="border px-2 py-1">{{ $grandTotals[$type]['total_so_quantity'] }}</td>
                        <td class="border px-2 py-1">{{ $grandTotals[$type]['total_quantity'] }}</td>
                        <td class="border px-2 py-1">{{ $grandTotals[$type]['total_balance'] }}</td>
                    </tr>
                @endforeach --}}
            </tbody>
        </table>

        <table class="table-auto w-full border mt-2 text-sm">
            <thead>
                <tr>
                    <th>Packing Type</th>
                    <th>SO Quantity</th>
                    <th>Dispatched Quantity</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center">Total Boray</td>
                    <td>{{ number_format($totalBoraySoQuantity, 2) }}</td>
                    <td>{{ number_format($totalBorayDispQuantity, 2) }}</td>
                </tr>
                <tr>
                    <td style="text-align: center">Total Carton</td>
                    <td>{{ number_format($totalCartonSoQuantity, 2) }}</td>
                    <td>{{ number_format($totalCartonDispQuantity, 2) }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th>Total Net Amount</th>
                    <th colspan="2">{{ number_format($totalNetAmount, 2) }}</th>
                </tr>
            </tfoot>
        </table>
        

        <!-- Footer -->
        <div class="flex justify-between mt-6 px-4">
            <div>
                <span class="font-bold">Created By:</span>
                <span class="border-b border-black inline-block w-32 ml-2">{{ $user }}</span>
            </div>
            <div>
                <span class="font-bold">Signed & Approved By:</span>
                <span class="border-b border-black inline-block w-32 ml-2"></span>
            </div>
        </div>

        <div class="text-center mt-4">
            <p>If you have any questions about this Document, Please contact</p>
            <p><b>Phone:</b> 0309 6662476 <b>Email:</b> info.amirfoods@gmail.com</p>
        </div>
    </div>
</body>

</html>









{{--
                <div class="row">
                    <div class="col md 12">
                        <div class="flex justify-start">
                            <table class="text-sm">
                                <tbody>

                                    <tr>
                                        <td class="border px-5 py-1"><b>Gross Amount.</b></td>
                                        <td class="border px-5 py-1">{{ $order->gross_bill ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="border px-5 py-1"><b>Fair.</b></td>
                                        <td class="border px-5 py-1">{{ $order->carriage ?? '-' }}</td>
                                    </tr>

                                    {{-- <tr>
                                        <td class="border px-5 py-1" colspan="2" style="padding: 0px 0px 0px 0px !important;">
                                            <table class="table2" style="width: 100%; border-width: 0% !important">
                                                <tr>
                                                    <td class="border px-2.5 py-0.5"><b>Commission.</b></td>
                                                    <td class="border px-2.5 py-0.5">{{ $order->party->commision ?? '-' }}%</td>
                                                    <td class="px-5 py-1" >{{ $order->carriage ?? '0.00' }}</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="border px-5 py-1"><b>Commission.</b> <span
                                                style="border-left: 1px !important; border-color: black; ">/
                                                {{ $order->party->commision ?? '-' }}%</span></td>
                                        <td class="px-5 py-1" style="">{{ $order->carriage ?? '0.00' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="border px-5 py-1"><b>Net Amount.</b></td>
                                        <td class="border px-5 py-1">{{ $order->net_amount ?? '-' }}</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <div class="flex justify-end">
                            <table class="text-sm">
                                <tbody>

                                    <tr>
                                        <td class="border px-5 py-1"><b>Gross Amount.</b></td>
                                        <td class="border px-5 py-1">{{ $order->gross_bill ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="border px-5 py-1"><b>Fair.</b></td>
                                        <td class="border px-5 py-1">{{ $order->carriage ?? '-' }}</td>
                                    </tr>

                                    {{-- <tr>
                                        <td class="border px-5 py-1" colspan="2" style="padding: 0px 0px 0px 0px !important;">
                                            <table class="table2" style="width: 100%; border-width: 0% !important">
                                                <tr>
                                                    <td class="border px-2.5 py-0.5"><b>Commission.</b></td>
                                                    <td class="border px-2.5 py-0.5">{{ $order->party->commision ?? '-' }}%</td>
                                                    <td class="px-5 py-1" >{{ $order->carriage ?? '0.00' }}</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="border px-5 py-1"><b>Commission.</b> <span
                                                style="border-left: 1px !important; border-color: black; ">/
                                                {{ $order->party->commision ?? '-' }}%</span></td>
                                        <td class="px-5 py-1" style="">{{ $order->carriage ?? '0.00' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="border px-5 py-1"><b>Net Amount.</b></td>
                                        <td class="border px-5 py-1">{{ $order->net_amount ?? '-' }}</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> --}}
