<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Sheet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .label {
            width: 60px;
            display: inline-block;
            font-weight: bold;
            font-size: 14px;
        }

        .value {
            width: 250px;
            display: inline-block;
        }
        .notes {
            border: 1px solid black;
            padding: 10px;
            margin-right: 10px;
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

        @foreach ($orders as $order)
            <div class="order-block">
                <div class="mb-1">
                    <span class="label">Order #</span>: <span class="value">{{ $order->id }}</span>
                    <span class="label">Date</span>: {{ $order->date }}
                </div>
                <div class="mb-1">
                    <span class="label">Party</span>: <span
                        class="value">{{ $dropDownData['parties'][$order->party_id] ?? '' }}</span>
                    <span class="label">Sector</span>: {{ $dropDownData['belts'][$order->belt] ?? '' }}
                </div>
                <div class="mb-2">
                    <span class="label">Sale Man</span>: <span
                        class="value">{{ $dropDownData['saleMans'][$order->saleman] ?? '' }}</span>
                    <span class="label">Area</span>: {{ $dropDownData['areas'][$order->area] ?? '' }}
                </div>
                <div class="mb-2">
                    <span class="label">To Party</span>: <span
                        class="value">{{ $dropDownData['deliveredToParties'][$order->delivered_to] ?? 'Same From Party' }}</span>
                </div>

                <table class="w-full text-left mb-4">
                    <thead style="background-color: #e5e7eb;">
                        <tr>
                            <th class="px-2 py-1" style="width: 30%;">Product</th>
                            <th class="px-2 py-1" style="width: 9%;">P/T</th>
                            <th class="px-2 py-1" style="width: 10%;">QTY</th>
                            <th class="px-1 py-0 bg-white"
                                style="border-bottom-color: white !important; width: 1%; border-top-color: white !important">
                            </th>
                            <th class="px-2 py-1" style="width: 30%;">Product</th>
                            <th class="px-2 py-1" style="width: 9%;">P/T</th>
                            <th class="px-2 py-1" style="width: 10%;">QTY</th>
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
                                <td class="px-2 py-1">{{ $order->details[$i]->quantity }}</td>

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
                                    <td class="px-2 py-1">{{ $order->details[$i + 1]->quantity }}</td>
                                @else
                                    {{-- <td class="px-2 py-1"></td> --}}
                                    <td class="px-2 py-1"></td>
                                    <td class="px-2 py-1"></td>
                                    <td class="px-2 py-1"></td>
                                @endif
                            </tr>
                        @endfor
                    </tbody>
                </table>

                <div class="row" style="display: flex;">
                    <div class="notes" style="width: 80%;">
                        <div>
                            <p><b>Remarks: </b> </p>
                        </div>
                    </div>
                    <div class="totals" style="width: 20%;">
                        <table class="text-sm">
                            <tbody>
                                <tr>
                                    <th class="px-2 py-1">Tot.Boray</th>
                                    <td class="px-2 py-1">{{ $order->total_boray ?? 0 }}</td>
                                    {{-- <th class="px-2 py-1">Tot.Carton</th>
                                    <td class="px-2 py-1">{{ $order->total_carton ?? 0 }}</td> --}}
                                </tr>
                                <tr>
                                    {{-- <th class="px-2 py-1">Tot.Boray</th>
                                    <td class="px-2 py-1">{{ $order->total_boray ?? 0 }}</td> --}}
                                    <th class="px-2 py-1">Tot.Carton</th>
                                    <td class="px-2 py-1">{{ $order->total_carton ?? 0 }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        @endforeach

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
