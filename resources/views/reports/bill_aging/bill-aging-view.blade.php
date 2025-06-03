<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Bill Aging Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Flex container for label-value pair */
        .label-value-pair {
            display: flex;
            align-items: center;
            gap: 0rem;
            white-space: nowrap;
        }

        .label {
            flex-shrink: 0;
            width: 100px;
            font-weight: bold;
            font-size: 14px;
        }

        .value {
            flex-grow: 1;
            white-space: nowrap;
            font-size: 14px;
        }

        .mono {
            font-family: Calibri, sans-serif;
        }

        .order-block {
            break-inside: avoid;
            page-break-inside: avoid;
            border: 2px solid black;
            padding: 0.5rem;
            margin-bottom: 0.5rem;
            background-color: #ffffff;
            border-radius: 0.25rem;
        }

        table,
        th,
        td {
            border: 1px solid lightgray !important;
            border-collapse: collapse;
        }

        /* New styles for header info container */
        .header-info-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0rem;
            /* margin-bottom: 1rem; */
        }

        .header-info-container>.mb-1 {
            flex: 0 0 32%;
            box-sizing: border-box;
        }

        .header-info-container>.mb-1:nth-child(3n) {
            margin-right: 0;
        }

        .notes {
            border: 1px solid lightgray;
            padding: 4px;
        }

        .notes div {
            height: 85px;
        }


        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            body {
                margin: 0;
                padding: 0;
                font-size: 10px;
            }

            .pagination-wrapper {
                display: none !important;
            }

            .print-container {
                width: 100vw;
                margin: 0;
                padding-left: 1mm;
                padding-right: 1mm;
            }

            @page {
                size: A4 portrait !important;
                margin: 2mm;
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
                font-size: 11px;
            }

            .order-block {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

            tr {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

            /* Print: 2 entities per row */
            .header-info-container {
                gap: 0px;
            }

            .header-info-container>.mb-1:nth-child(-n+3) {
                flex: 0 0 33% !important;
            }

            .header-info-container>.mb-1:nth-child(n+4) {
                flex: 0 0 49% !important;
            }

            .header-info-container>.mb-1 {
                margin-right: 0 !important;
                margin-bottom: 0rem;
                break-inside: avoid !important;
                page-break-inside: avoid !important;
                box-sizing: border-box;
            }

            .label-value-pair {
                white-space: nowrap;
                display: flex !important;
                align-items: center !important;
                gap: 0rem !important;
            }

            .label {
                width: 100px !important;
                flex-shrink: 0 !important;
                font-weight: bold !important;
                font-size: 12px !important;
            }

            .value {
                flex-grow: 1 !important;
                white-space: nowrap !important;
                font-size: 12px !important;
            }
        }
    </style>
</head>

<body class="bg-white text-black text-sm mono p-2">
    <div class="header flex justify-between items-center p-4 border-b border-black">
        <div class="flex items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Amir Foods Logo" class="h-16 w-16" />
            <div class="ml-4">
                <p class="font-bold text-lg">AAMIR BROTHERS FOOD PRODUCTS MULTAN</p>
                <p>12KM Vehari Road Multan <span class="font-bold">CELL:</span> 0309 6662476</p>
                <p><span class="font-bold">EMAIL:</span> info.amirfoods@gmail.com</p>
            </div>
        </div>
        <div class="text-right">
            <p class="font-bold text-xl">{{ $title }}</p>
        </div>
    </div>

    {{-- <div class="pagination-wrapper mt-4">
        {{ $dispatchReportMasters->links('pagination::tailwind') }}
    </div> --}}

    <div class="header-info-container">
        <div class="mb-1">
            <div class="label-value-pair">
                <span class="label">From Date:</span>
                <span class="value">{{ \Carbon\Carbon::parse($fromDate)->format('d-F-Y') }}</span>
            </div>
        </div>
        <div class="mb-1">
            <div class="label-value-pair">
                <span class="label">To Date:</span>
                <span class="value">{{ \Carbon\Carbon::parse($toDate)->format('d-F-Y') }}</span>
            </div>
        </div>
    </div>

    <div class="print-container w-full mx-auto">
        {{-- @foreach ($orders as $order)
            <div class="order-block">

                {{-- Wrap all header info blocks in this container
                <div class="header-info-container">
                    <div class="mb-1">
                        <div class="label-value-pair">
                            <span class="label">Invoice No/Date:</span>
                            <span
                                class="value">SI-{{ $order->id }}({{ optional($order->date)->format('d-F-Y') }})</span>
                        </div>
                    </div>

                    <div class="mb-1">
                        <div class="label-value-pair">
                            <span class="label">SO#/Date:</span>
                            <span
                                class="value">{{ $order->sale_order_number ?? '' }}({{ optional(optional($order->sale_order_master)->date)->format('d-F-Y') }})</span>
                        </div>
                    </div>

                    <div class="mb-1">
                        <div class="label-value-pair">
                            <span class="label">Disp#/Date:</span>
                            <span
                                class="value">{{ $order->dispatch_note_number ?? '' }}({{ optional(optional($order->dispatch_note_master)->date)->format('d-F-Y') }})</span>
                        </div>
                    </div>

                    <div class="mb-1">
                        <div class="label-value-pair">
                            <span class="label">Party:</span>
                            <span class="value">{{ $dropDownData['parties'][$order->party_id] ?? '' }}</span>
                        </div>
                    </div>

                    <div class="mb-1">
                        <div class="label-value-pair">
                            <span class="label">Delivered To:</span>
                            <span
                                class="value">{{ $dropDownData['deliveredToParties'][$order->delivered_to] ?? 'Same From Party' }}</span>
                        </div>
                    </div>

                    <div class="mb-1">
                        <div class="label-value-pair">
                            <span class="label">Sale Man:</span>
                            <span class="value">{{ $dropDownData['saleMans'][$order->saleman] ?? '' }}</span>
                        </div>
                    </div>

                    <div class="mb-1">
                        <div class="label-value-pair">
                            <span class="label">Sector:</span>
                            <span class="value">{{ $dropDownData['belts'][$order->sector] ?? '' }}</span>
                        </div>
                    </div>
                    <div class="mb-1">
                        <div class="label-value-pair">
                            <span class="label">Transporter:</span>
                            <span
                                class="value">{{ $dropDownData['transporters'][$order->transporter_id] ?? '' }}</span>
                        </div>
                    </div>
                    <div class="mb-1">
                        <div class="label-value-pair">
                            <span class="label">Area:</span>
                            <span class="value">{{ $dropDownData['areas'][$order->area] ?? '' }}</span>
                        </div>
                    </div>


                    <div class="mb-1">
                        <div class="label-value-pair">
                            <span class="label">Bility No:</span>
                            <span class="value">{{ $order->bilty_no ?? '' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Table
                <table class="w-full mb-4 text-sm table-fixed">
                    <thead class="bg-gray-200">
                        <tr>
                            <th style="width: 5%">Sr #</th>
                            <th style="width: 30%">Product</th>
                            <th style="width: 5%">P/T</th>
                            <th style="width: 7.5%">SO/QTY</th>
                            <th style="width: 7.5%">Disp/QTY</th>
                            <th style="width: 10%">T.Dzns</th>
                            <th style="width: 10%">Rate</th>
                            <th style="width: 10%">Discount</th>
                            <th style="width: 15%">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->details as $index => $detail)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $dropDownData['products'][$detail->product_id] ?? 'Unknown' }}</td>
                                <td class="text-center">{{ $detail->packing_type }}</td>
                                <td class="text-right">{{ number_format($detail->soQuantity ?? 0, 2) }}</td>
                                <td class="text-right">{{ number_format($detail->dispQuantity ?? 0, 2) }}</td>
                                <td class="text-right">{{ number_format($detail->total_dzns ?? 0, 2) }}</td>
                                <td class="text-right">{{ number_format($detail->rate ?? 0, 2) }}</td>
                                <td class="text-right">{{ number_format($detail->discount ?? 0, 2) }}</td>
                                <td class="text-right">{{ number_format($detail->amount ?? 0, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Totals
                <div class="flex gap-2">
                    <div style="width: 53% !important">
                        <div class="notes">
                            <div>
                                <p><b>Remarks: </b>{{ $order->remarks ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                    <div style="width: 20% !important">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-200">
                                <tr>

                                    <th>Detail</th>
                                    <th>Ord/QTY</th>
                                    <th>Disp/QTY</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>

                                    <td><b>Tot.Boray</b></td>
                                    <td class="text-right">{{ $order->boray_so_quantity }}</td>
                                    <td class="text-right">{{ $order->boray_disp_quantity }}</td>

                                </tr>
                                <tr>
                                    <td><b>Tot.Carton</b></td>
                                    <td class="text-right">{{ $order->carton_so_quantity }}</td>
                                    <td class="text-right">{{ $order->carton_disp_quantity }}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <div style="width: 27% !important">
                        <table class="w-full text-sm">
                            <tbody>
                                <tr>
                                    <td style="width: 4% !important"><b>Gross Amount</b></td>
                                    <td class="text-right" style="width: 10% !important">
                                        <b>{{ $order->gross_bill ?? '-' }}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Fair</b></td>
                                    <td class="text-right"><b>{{ $order->carriage ?? '-' }}</b></td>
                                </tr>
                                <tr>
                                    <td><b>Commission</b>-{{ $order->party->commision ?? '-' }}%</td>
                                    <td class="text-right"><b>{{ $order->commission_amount ?? '0.00' }}</b></td>
                                </tr>
                                <tr>
                                    <td><b>Net Amount</b></td>
                                    <td class="text-right"><b>{{ $order->net_amount ?? '-' }}</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach --}}

        {{-- @foreach ($finalData as $partyRows)
            <div class="order-block">

                {{-- Wrap all header info blocks in this container
                <div class="header-info-container">
                    <div class="mb-1">
                        <div class="label-value-pair">
                            <span class="label">Party:</span>
                            <span class="value">{{ $partyRows[0]['party_name'] }}</span>
                        </div>
                    </div>


                    <div class="mb-1">
                        <div class="label-value-pair">
                            <span class="label">Sale Man:</span>
                            <span class="value">{{ $partyRows[0]['salesman'] }}</span>
                        </div>
                    </div>

                    <div class="mb-1">
                        <div class="label-value-pair">
                            <span class="label">Zone:</span>
                            <span class="value">{{ $partyRows[0]['zone'] }}</span>
                        </div>
                    </div>
                    <div class="mb-1">
                        <div class="label-value-pair">
                            <span class="label">Belt:</span>
                            <span class="value">{{ $partyRows[0]['belt'] }}</span>
                        </div>
                    </div>
                    <div class="mb-1">
                        <div class="label-value-pair">
                            <span class="label">Area:</span>
                            <span class="value">{{ $partyRows[0]['area'] }}</span>
                        </div>
                    </div>
                    <div class="mb-1">
                        <div class="label-value-pair">
                            <span class="label">Credit Days:</span>
                            <span class="value">{{ $partyRows[0]['credit_days'] }}</span>
                        </div>
                    </div>
                     <div class="mb-1">
                        <div class="label-value-pair">
                            <span class="label">Credit Limit:</span>
                            <span class="value">{{ $partyRows[0]['credit_limit'] }}</span>
                        </div>
                    </div>
                </div>

                {{-- <h4>Party: {{ $partyRows[0]['party_name'] }}</h4>

                {{-- <h4>Sale Man: {{ $partyRows[0]['salesman'] }}</h4>
                <table class="w-full mb-4 text-sm table-fixed">
                    <thead class="bg-gray-200">
                        <tr>
                            <th style="width: 10%">Invoice Date</th>
                            <th style="width: 25%">Transporter</th>
                            <th style="width: 10%">Invoice No</th>
                            <th style="width: 10%">Credit Days</th>
                            <th style="width: 15%">Net Amount</th>
                            <th style="width: 15%">Due Amount</th>
                            <th style="width: 15%">Total Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($partyRows as $row)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($row['invoice_date'])->format('d-M-Y') }}</td>
                                <td>{{ $row['transporter'] }}</td>
                                <td>{{ $row['invoice_number'] }}</td>
                                <td>{{ $row['days_since_invoice'] }}</td>
                                <td>{{ number_format($row['net_amount'], 2) }}</td>
                                <td>{{ number_format($row['due_amount'], 2) }}</td>
                                <td>{{ number_format($row['total_balance'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach --}}


        {{-- @foreach ($groupedInvoices as $group)
            @foreach ($sectors as $sectorId => $invoices)
                @php
                    $firstInvoice = $invoices->first();
                    $partyName = $firstInvoice->party_name;
                    $sectorName = $firstInvoice->sector_name;
                    $totalDue = $invoices->sum('amount_due');
                    $total_0_30 = $invoices->sum('amount_due_0_30');
                    $total_31_60 = $invoices->sum('amount_due_31_60');
                    $total_61_90 = $invoices->sum('amount_due_61_90');
                    $total_91_120 = $invoices->sum('amount_due_91_120');
                    $total_120_plus = $invoices->sum('amount_due_120_plus');
                @endphp

                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <strong>Party:</strong> {{ $partyName }} |
                        <strong>Sector:</strong> {{ $sectorName }}
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Invoice No</th>
                                    <th>Invoice Date</th>
                                    <th>Due Date</th>
                                    <th>Credit Days</th>
                                    <th>Transporter</th>
                                    <th>Amount</th>
                                    <th>Amount Due</th>
                                    <th>0-30 Days</th>
                                    <th>31-60 Days</th>
                                    <th>61-90 Days</th>
                                    <th>91-120 Days</th>
                                    <th>120+ Days</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoices as $invoice)
                                    <tr>
                                        <td>{{ $invoice->invoice_no }}</td>
                                        <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-m-Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d-m-Y') }}</td>
                                        <td>{{ $invoice->credit_days }}</td>
                                        <td>{{ $invoice->transporter_name ?? '-' }}</td>
                                        <td class="text-end">{{ number_format($invoice->amount, 2) }}</td>
                                        <td class="text-end">{{ number_format($invoice->amount_due, 2) }}</td>
                                        <td class="text-end">{{ number_format($invoice->amount_due_0_30, 2) }}</td>
                                        <td class="text-end">{{ number_format($invoice->amount_due_31_60, 2) }}</td>
                                        <td class="text-end">{{ number_format($invoice->amount_due_61_90, 2) }}</td>
                                        <td class="text-end">{{ number_format($invoice->amount_due_91_120, 2) }}</td>
                                        <td class="text-end">{{ number_format($invoice->amount_due_120_plus, 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="bg-light fw-bold">
                                    <td colspan="6" class="text-end">Subtotal</td>
                                    <td class="text-end">{{ number_format($totalDue, 2) }}</td>
                                    <td class="text-end">{{ number_format($total_0_30, 2) }}</td>
                                    <td class="text-end">{{ number_format($total_31_60, 2) }}</td>
                                    <td class="text-end">{{ number_format($total_61_90, 2) }}</td>
                                    <td class="text-end">{{ number_format($total_91_120, 2) }}</td>
                                    <td class="text-end">{{ number_format($total_120_plus, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        @endforeach --}}

        @foreach ($groupedInvoices as $group)

            <div class="card my-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Party: {{ $group['party'] }}</h5>
                    <small>
                        Zone: {{ $group['zone'] }} |
                        Belt: {{ $group['belt'] }} |
                        Area: {{ $group['area'] }} |
                        Salesman: {{ $group['salesman'] }} |
                        Credit Days: {{ $group['credit_days'] }} |
                        Credit Limit: {{ number_format($group['credit_limit'], 2) }}
                    </small>
                </div>

                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped table-sm mb-0">
                        <thead class="table-secondary">
                            <tr>
                                <th>#</th>
                                <th>Invoice #</th>
                                <th>Date</th>
                                <th>Credit Days</th>
                                <th>Days Passed</th>
                                <th>Net Amount</th>
                                <th>Due Amount</th>
                                <th>Total Balance</th>
                                <th>Transporter</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($group['invoices'] as $index => $invoice)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $invoice['invoice_number'] }}</td>
                                    <td>{{ $invoice['invoice_date'] }}</td>
                                    <td>{{ $invoice['credit_days'] }}</td>
                                    <td>{{ $invoice['days_since_invoice'] }}</td>
                                    <td class="text-end">{{ number_format($invoice['net_amount'], 2) }}</td>
                                    <td class="text-end">{{ number_format($invoice['due_amount'], 2) }}</td>
                                    <td class="text-end">{{ number_format($invoice['total_balance'], 2) }}</td>
                                    <td>{{ $invoice['transporter'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

        @if (empty($finalData))
            <div class="alert alert-info text-center">
                No records found for the selected filters.
            </div>
        @endif


        {{-- Summary Table --}}
        <table class="w-full mt-2 text-sm border">
            <thead class="bg-gray-200">
                <tr>
                    <th>Packing Type</th>
                    <th>SO Quantity</th>
                    <th>Dispatched Quantity</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center"><b>G.Tot Boray</b></td>
                    {{-- <td>{{ number_format($totalBoraySoQuantity, 2) }}</td>
                    <td>{{ number_format($totalBorayDispQuantity, 2) }}</td> --}}
                </tr>
                <tr>
                    <td class="text-center"><b>G.Tot Carton</b></td>
                    {{-- <td>{{ number_format($totalCartonSoQuantity, 2) }}</td>
                    <td>{{ number_format($totalCartonDispQuantity, 2) }}</td> --}}
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold;">Tot.Net Amounts:</td>
                    {{-- <td colspan="2" style="font-weight: bold; text-align: center;">
                        {{ number_format($totalNetAmount, 2) }}</td> --}}
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
