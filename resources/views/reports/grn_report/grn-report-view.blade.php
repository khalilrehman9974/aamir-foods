<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Purchase Order Report</title>
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
            height: 80px;
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
        {{ $grnMasters->links('pagination::tailwind') }}
    </div>

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
        @foreach ($orders as $order)
            <div class="order-block">

                {{-- Wrap all header info blocks in this container --}}
                <div class="header-info-container">
                    <div class="mb-1">
                        <div class="label-value-pair">
                            <span class="label">Date/GRN#:</span>
                            <span class="value">{{ \Carbon\Carbon::parse($order->date ?? null)->format('d-F-Y') }}
                                (GRN-{{ $order->id }})</span>
                        </div>
                    </div>

                    <div class="mb-1">
                        <div class="label-value-pair">
                            <span class="label">Date/PO#:</span>
                            <span
                                class="value">{{ optional($order->poMasters)->date ? \Carbon\Carbon::parse($order->poMasters->date)->format('d-F-Y') : '' }}(P0-{{ $order->purchase_order_no ?? '-' }})</span>
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
                            <span class="label">Fare:</span>
                            <span class="value">{{ $order->fare ?? '' }}</span>
                        </div>
                    </div>

                    <div class="mb-1">
                        <div class="label-value-pair">
                            <span class="label">Supplier Bill# :</span>
                            <span class="value">{{ $order->supplier_bill_no ?? '' }}</span>
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
                            <span class="label">Unloaded By:</span>
                            <span class="value">{{ $order->unloaded_by ?? '' }}</span>
                        </div>
                    </div>

                </div>

                {{-- Table --}}
                <table class="w-full mb-4 text-sm table-fixed">
                    <thead class="bg-gray-200">
                        <tr>
                            <th style="width: 5%">Sr #</th>
                            <th style="width: 20%">Product</th>
                            <th style="width: 5%">P/T</th>
                            <th style="width: 5%">M/T</th>
                            <th style="width: 5%">Size</th>
                            <th style="width: 7.5%">Bags</th>
                            <th style="width: 10%">MT</th>
                            <th style="width: 10%">PO/Qty</th>
                            <th style="width: 10%">Rec/Qty</th>
                            <th style="width: 10%">Balance</th>
                            <th style="width: 12.5%">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->details as $index => $detail)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $dropDownData['products'][$detail->product_id] ?? 'Unknown' }}</td>
                                <td class="text-center">{{ $detail->packing_type }}</td>
                                <td class="text-center">{{ $detail->measurement_type }}</td>
                                <td class="text-center">{{ $detail->size ?? '-' }}</td>
                                <td class="text-center">{{ $detail->bags ?? '-' }}</td>
                                 <td class="text-center">{{ $detail->measurementType ?? '-' }}</td>
                                <td class="text-right">{{ number_format($detail->po_quantity ?? 0, 2) }}</td>
                                <td class="text-right">{{ number_format($detail->received_qty ?? 0, 2) }}</td>
                                <td class="text-right">{{ number_format($detail->balance ?? 0, 2) }}</td>
                                <td class="text-center">{{ $detail->detail_remarks ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Totals --}}
                <div class="flex gap-2">
                    <div style="width: 70% !important">
                        <div class="notes">
                            <div>
                                <p><b>Remarks: </b>{{ $order->remarks ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                    <div style="width: 30% !important">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-200">
                                <tr>

                                    <th style="width: 50%">Detail</th>
                                    <th style="width: 50%"></th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>

                                    <td><b>Total Quantity:</b></td>
                                    <td class="text-right">{{ $order->total_quantity }}</td>
                                    {{-- <td class="text-right">{{ $order->boray_disp_quantity }}</td> --}}

                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="summary" style="margin-top: 20px !important; margin-bottom: 20px !important;">
            <h1 style="font-size:30px; color:red"><b>Summary:</b></h1>
        </div>

        {{-- Summary Table --}}
        <table class="mt-2 text-sm border" style="width: 50% !important">
            <thead>
                <tr>
                    <th class="bg-gray-200">Grand Total Quantity</th>
                    <td>{{ number_format($totalVisibleQuantity, 2) }}</td>
                </tr>
            </thead>

        </table>
    </div>
</body>

</html>
