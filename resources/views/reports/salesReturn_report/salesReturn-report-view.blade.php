<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sale Invoice Report</title>
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

    <div class="pagination-wrapper mt-4">
        {{ $dispatchReportMasters->links('pagination::tailwind') }}
    </div>

    <div class="header-info-container">
        <div class="mb-1">
            <div class="label-value-pair">
                <span class="label">From Date:</span>
                <span class="value">{{ $fromDate ? \Carbon\Carbon::parse($fromDate)->format('d-F-Y') : '' }}</span>
            </div>
        </div>
        <div class="mb-1">
            <div class="label-value-pair">
                <span class="label">To Date:</span>
                <span class="value">{{ $toDate ? \Carbon\Carbon::parse($toDate)->format('d-F-Y') : '' }}</span>
            </div>
        </div>
    </div>

    <div class="print-container w-full mx-auto">
        @foreach ($orders as $order)
            <div class="order-block">

                {{-- Wrap all header info blocks in this container --}}
                <div class="header-info-container">
                    <div class="mb-1">
                        <div class="label-value-pair">
                            <span class="label">SRI#/Date:</span>
                            <span
                                class="value">SR-{{ $order->id }} ({{ \Carbon\Carbon::parse($order->date ?? null)->format('d-F-Y') }})</span>
                        </div>
                    </div>

                    <div class="mb-1">
                        <div class="label-value-pair">
                            <span class="label">GRN#/Date:</span>
                            {{-- <span
                                class="value">{{ $order->grn_no ?? '' }}(  {{ optional(optional($order->grnMasters)->date)->format('d-F-Y') }})</span> --}}
                            <span class="value">
                                GRN-{{ $order->grn_no ?? '' }} ({{ optional($order->grn_master)->date ? \Carbon\Carbon::parse($order->grn_master->date)->format('d-F-Y') : '' }})
                            </span>
                        </div>
                    </div>

                    <div class="mb-1">
                        <div class="label-value-pair">
                            <span class="label">SI#/Date:</span>
                            <span
                                class="value">SI-{{ $order->sale_invoice_number ?? '' }} ({{ optional($order->sale_master)->date ? \Carbon\Carbon::parse($order->sale_master->date)->format('d-F-Y') : '' }})</span>


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
                                class="value">{{ $dropDownData['deliveredToParties'][$order->deliverd_to] ?? 'Same From Party' }}</span>
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

                {{-- Table --}}
                <table class="w-full mb-4 text-sm table-fixed">
                    <thead class="bg-gray-200">
                        <tr>
                            <th style="width: 5%">Sr #</th>
                            <th style="width: 30%">Product</th>
                            <th style="width: 10%">P/T</th>
                            <th style="width: 10%">QTY</th>
                            <th style="width: 10%">T.Dzns</th>
                            <th style="width: 10%">Rate</th>
                            <th style="width: 15%">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->details as $index => $detail)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $dropDownData['products'][$detail->product_id] ?? 'Unknown' }}</td>
                                <td class="text-center">{{ $detail->packing_type }}</td>
                                <td class="text-right">{{ number_format($detail->quantity ?? 0, 2) }}</td>
                                <td class="text-right">{{ number_format($detail->total_dzns ?? 0, 2) }}</td>
                                <td class="text-right">{{ number_format($detail->rate ?? 0, 2) }}</td>
                                <td class="text-right">{{ number_format($detail->amount ?? 0, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Totals --}}
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
                                    <th>Qty</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>

                                    <td><b>Tot.Boray</b></td>
                                    <td class="text-right">{{ $order->boray_amount }}</td>

                                </tr>
                                <tr>
                                    <td><b>Tot.Carton</b></td>
                                    <td class="text-right">{{ $order->carton_amount }}</td>
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
                                        <b>{{ number_format($order->gross_amount ?? 0, 2) }}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Discount</b></td>
                                    <td class="text-right"><b>{{ number_format($order->scheme ?? 0, 2) }}</b></td>
                                </tr>
                                <tr>
                                    <td><b>Commission</b>-{{ $order->party->commision ?? '-' }}%</td>
                                    <td class="text-right"><b>{{ number_format($order->commission ?? 0, 2) }}</b></td>
                                </tr>
                                <tr>
                                    <td><b>Net Amount</b></td>
                                    <td class="text-right"><b>{{ number_format($order->net_amount ?? 0, 2) }}</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="summary" style="margin-top: 20px !important; margin-bottom: 20px !important;">
            <h1 style="font-size:30px;"><b>Summary</b></h1>
        </div>
        {{-- Summary Table --}}
        <table class=" mt-2 text-sm border" style="width: 50% !important;">


            <thead class="bg-gray-200">
                <tr>
                    <th>G.Tot Boray</th>
                    <th>G.Tot Carton</th>
                    <th>Tot.Net Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="font-weight: bold; text-align: center;"><b>{{ number_format($totalBorayQuantity, 2) }}</b></td>
                    <td style="font-weight: bold; text-align: center;"><b>{{ number_format($totalCartonQuantity, 2) }}</b></td>
                    <td style="font-weight: bold; text-align: center;">
                        {{ number_format($totalNetAmount, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
