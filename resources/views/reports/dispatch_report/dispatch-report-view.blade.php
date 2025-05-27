<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dispatch Report</title>
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
            /* Tailwind gray-100 */
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
        @foreach ($orders as $order)
            <div class="order-block">
                <div class="mb-1">
                    <span class="label">Dispatch Note #</span>: <span class="value">{{ $order->id }}</span>
                    <span class="label">Date</span>: {{ $order->date }}
                </div>
                <div class="mb-1">
                    <span class="label">SO #</span>: <span class="value">{{ $order->sale_order_number }}</span>
                    <span class="label">Status</span>: {{ $order->status }}
                </div>
                <div class="mb-1">
                    <span class="label">Party</span>: <span
                        class="value">{{ $dropDownData['parties'][$order->party_id] ?? '' }}</span>
                    <span class="label">Sector</span>: {{ $dropDownData['belts'][$order->sector] ?? '' }}
                </div>
                <div class="mb-2">
                    <span class="label">Sale Man</span>: <span
                        class="value">{{ $dropDownData['saleMans'][$order->saleman] ?? '' }}</span>
                    <span class="label">Area</span>: {{ $dropDownData['areas'][$order->area] ?? '' }}
                </div>
                <div class="mb-2">
                    <span class="label">To Party</span>: <span
                        class="value">{{ $dropDownData['deliveredToParties'][$order->delivered_to] ?? 'Same From Party' }}</span>
                    <span class="label">Bility No</span>: {{ $order->bility_no ?? '' }}
                </div>
                <div class="mb-2">
                    <span class="label">Transporter</span>: <span
                        class="value">{{ $dropDownData['transporters'][$order->transporter_id] ?? '' }}</span>
                    <span class="label">Vehicle No</span>: {{ $order->vehicle_no ?? '' }}
                </div>

                <table class="w-full text-left mb-4">
                    <thead style="background-color: #e5e7eb;">
                        <tr>
                            <th class="px-2 py-1" style="width: 20%;">Product</th>
                            <th class="px-2 py-1" style="width: 5%;">P/T</th>
                            <th class="px-2 py-1" style="width: 8%;">SO QTY</th>
                            <th class="px-2 py-1" style="width: 8%;">QTY</th>
                            <th class="px-2 py-1" style="width: 8%;">Variation</th>
                            <th class="px-1 py-0 bg-white"
                                style="border-bottom-color: white !important; width: 1%; border-top-color: white !important">
                            </th>
                            <th class="px-2 py-1" style="width: 20%;">Product</th>
                            <th class="px-2 py-1" style="width: 5%;">P/T</th>
                            <th class="px-2 py-1" style="width: 8%;">SO QTY</th>
                            <th class="px-2 py-1" style="width: 8%;">QTY</th>
                            <th class="px-2 py-1" style="width: 8%;">Variation</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $serial = 1;
                            $count = count($order->details);
                        @endphp

                        @for ($i = 0; $i < $count; $i += 2)
                            <tr>
                                {{-- First entry --}}
                                {{-- <td class="px-2 py-1"><b>{{ $serial++ }}</b></td> --}}
                                <td class="px-2 py-1">
                                    {{ $dropDownData['products'][$order->details[$i]->product_id] ?? 'Unknown' }}</td>
                                <td class="px-2 py-1">{{ $order->details[$i]->packing_type }}</td>
                                <td class="px-2 py-1">{{ $order->details[$i]->soQuantity }}</td>
                                <td class="px-2 py-1">{{ $order->details[$i]->quantity }}</td>
                                <td class="border px-2 py-1">{{ $order->details[$i]->balance ?? 0 }}</td>

                                <td class="px-0 py-0 bg-white"
                                    style="border-bottom-color: white !important; border-top-color: white !important">
                                </td>

                                {{-- Second entry --}}
                                @if (isset($order->details[$i + 1]))
                                    {{-- <td class="px-2 py-1"><b>{{ $serial++ }}</b></td> --}}
                                    <td class="px-2 py-1">
                                        {{ $dropDownData['products'][$order->details[$i + 1]->product_id] ?? 'Unknown' }}
                                    </td>
                                    <td class="px-2 py-1">{{ $order->details[$i + 1]->packing_type }}</td>
                                    <td class="px-2 py-1">{{ $order->details[$i + 1]->soQuantity }}</td>
                                    <td class="px-2 py-1">{{ $order->details[$i + 1]->quantity }}</td>
                                    <td class="border px-2 py-1">{{ $order->details[$i + 1]->balance ?? 0 }}</td>
                                @else
                                    {{-- <td class="px-2 py-1"></td> --}}
                                    <td class="px-2 py-1"></td>
                                    <td class="px-2 py-1"></td>
                                    <td class="px-2 py-1"></td>
                                    <td class="px-2 py-1"></td>
                                    <td class="px-2 py-1"></td>
                                @endif
                            </tr>
                        @endfor
                    </tbody>
                </table>

                <div class="flex justify-end">
                    <table class="text-sm">
                        <tbody>
                            @php
                                $boray = $order->packing_totals['Boray'] ?? [
                                    'total_so_quantity' => 0,
                                    'total_quantity' => 0,
                                    'total_balance' => 0,
                                ];
                                $carton = $order->packing_totals['Carton'] ?? [
                                    'total_so_quantity' => 0,
                                    'total_quantity' => 0,
                                    'total_balance' => 0,
                                ];
                            @endphp
                            <tr>
                                <th class="px-2 py-1">Detail</th>
                                <th class="px-2 py-1">SO QTY</th>
                                <th class="px-2 py-1">Disp/Qty</th>
                                <th class="px-2 py-1">Variation</th>

                            </tr>
                            {{-- <tr>
                                <th class="px-2 py-1">Tot.Boray</th>
                                <td class="px-2 py-1">{{ $order->total_boray ?? 0 }}</td>
                                <th class="px-2 py-1">Tot.Carton</th>
                                <td class="px-2 py-1">{{ $order->total_carton ?? 0 }}</td>
                            </tr> --}}
                            <tr>
                                <td class="border px-2 py-1"><b>Boray</b></td>
                                <td class="border px-2 py-1">{{ $boray['total_so_quantity'] }}</td>
                                <td class="border px-2 py-1">{{ $boray['total_quantity'] }}</td>
                                <td class="border px-2 py-1">{{ $boray['total_balance'] }}</td>
                            </tr>
                            <tr>
                                <td class="border px-2 py-1"><b>Carton</b></td>
                                <td class="border px-2 py-1">{{ $carton['total_so_quantity'] }}</td>
                                <td class="border px-2 py-1">{{ $carton['total_quantity'] }}</td>
                                <td class="border px-2 py-1">{{ $carton['total_balance'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

        <h3 class="text-lg font-bold mt-4">Grand Totals by Packing Type</h3>
        <table class="table-auto w-full border border-gray-400 mt-2 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-2 py-1">Packing Type</th>
                    <th class="border px-2 py-1">Total SO Quantity</th>
                    <th class="border px-2 py-1">Total Quantity</th>
                    <th class="border px-2 py-1">Balance</th>
                </tr>
            </thead>
            <tbody>
                @foreach (['Boray', 'Carton'] as $type)
                    <tr>
                        <td class="border px-2 py-1">{{ $type }}</td>
                        <td class="border px-2 py-1">{{ $grandTotals[$type]['total_so_quantity'] }}</td>
                        <td class="border px-2 py-1">{{ $grandTotals[$type]['total_quantity'] }}</td>
                        <td class="border px-2 py-1">{{ $grandTotals[$type]['total_balance'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>


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
