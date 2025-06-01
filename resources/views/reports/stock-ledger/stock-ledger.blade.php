<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: Calibri, sans-serif;
            color: black;
            background-color: white;
        }

        .container {
            width: 100%;
            max-width: 210mm;
            /* A4 width in portrait */
            margin: 0 auto;
            padding: 20px;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
                font-size: 12px;
                /* Set base font size for printing */
            }

            .container {
                width: 100%;
                max-width: 210mm;
                /* A4 width */
                height: auto;
                /* Allow content to flow naturally */
                page-break-after: auto;
            }

            /* Ensure portrait mode */
            @page {
                size: A4 portrait;
                /* Explicitly set portrait mode */
                margin: 10mm;
                /* Adjust margin if needed */
            }

            /* Adjust specific font sizes for better readability */
            h1 {
                font-size: 18px;
            }

            h2 {
                font-size: 16px;
            }

            h3 {
                font-size: 14px;
            }

            p,
            td,
            th {
                font-size: 12px;
            }
        }

        .header,
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header img {
            width: 100px;
            height: 100px;
        }

        .header div,
        .footer div {
            text-align: left;
        }

        .header div,
        {
        margin-bottom: 20px;
        }

        .header div p,
        .footer div p {
            margin: 0;
        }

        .header h1 {
            font-size: 24px;
            font-weight: bold;
        }

        .info,
        .table-container,
        .notes,
        .signatures,
        .contact,
        .totals {
            margin-bottom: 10px;
        }

        .info div,
        .signatures div {
            display: flex;
            justify-content: space-between;
        }

        .info div p,
        .signatures div p {
            margin: 0;
        }

        .table-container table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-container th,
        .table-container td {
            border: 1px solid gray;
            padding: 3px;
            text-align: left;
        }

        .notes {
            border: 1px solid gray;
            padding: 10px;
            margin-right: 10px;
        }

        .notes div {
            height: 50px;
        }

        .signatures div {
            height: 30px;
        }

        .contact p {
            text-align: center;
            margin: 0;
        }

        .totals {
            width: 30%;
            text-align: left;
            border-collapse: collapse;
            margin-right: 5px;
        }

        .totals table {
            float: left;
        }

        .p-4{
            padding: 0px !important;
        }

        .totals th,
        .totals td {
            border: 1px solid gray;
            padding: 8px;
            text-align: left;
        }

        .totals th {
            font-weight: bold;
            width: 50%;
        }

        .header {
            text-align: center;
            /* padding: 5px; */
        }

        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .line {
            border-top: 2px solid black;
            width: 100%;
            margin: 0 auto;
        }

        .line+.line {
            margin-top: 2px;
        }
    </style>
</head>
<body>

    <?php
        if (!function_exists('calculateStockBalance')) {
            function calculateStockBalance($openingBalance, $entries) {

                $balance = (float)$openingBalance;

                foreach ($entries as $key => $entry) {
                    // $valOfStock = 0; // Initialize val of stock

                    if (!empty($entry['stock_in_quantity'])) {
                        $balance += (float)$entry['stock_in_quantity'];
                    }

                    if (!empty($entry['stock_out_quantity'])) {
                        $balance -= (float)$entry['stock_out_quantity'];
                    }

                    $valOfStock = $balance * (float)$entry['rate']; // Correct calculation of Value of Stock

                    $entries[$key]['Balance'] = number_format($balance, 2); // Format for readability
                    $entries[$key]['Val_of_Stock'] = number_format($valOfStock, 2);

                }

                return $entries;
            }
        }

        // Function to calculate total 'stock_in_quantity'
        if (!function_exists('calculateTotalStockInQuantity')) {
            function calculateTotalStockInQuantity($openingBalance, $entries) {
                $totalQuantity = (float)$openingBalance;
                foreach ($entries as $entry) {
                    if (!empty($entry['stock_in_quantity'])) {
                        $totalQuantity += (int)$entry['stock_in_quantity'];
                    }
                }
                return $totalQuantity;
            }
        }

        if (!function_exists('calculateTotalStockOutQuantity')) {
            function calculateTotalStockOutQuantity($entries) {
                $totalOutQuantity = 0;
                foreach ($entries as $entry) {
                    if (!empty($entry['stock_out_quantity'])) {
                        $totalOutQuantity += (int)$entry['stock_out_quantity'];
                    }
                }
                return $totalOutQuantity;
            }
        }

        if (!function_exists('calculateTotalStockInBags')) {
            function calculateTotalStockInBags($entries) {
                $totalInBags = 0;
                foreach ($entries as $entry) {
                    if (!empty($entry['stock_in_bags'])) {
                        $totalInBags += (int)$entry['stock_in_bags'];
                    }
                }
                return $totalInBags;
            }
        }

        if (!function_exists('calculateTotalStockOutBags')) {
            function calculateTotalStockOutBags($entries) {
                $totalOutBags = 0;
                foreach ($entries as $entry) {
                    if (!empty($entry['stock_out_bags'])) {
                        $totalOutBags += (int)$entry['stock_out_bags'];
                    }
                }
                return $totalOutBags;
            }
        }

        // Sample data from the provided image
        // $openingBalance = $product->opening_stock;
        $openingBalance = is_numeric($product->opening_stock) ? (float)$product->opening_stock : 0;
        if ($openingBalance === 0) {
            $openingStockRate = 0;
        } else {
            $openingStockRate = $product->stock_rate;
        }

        $entries = $stockLedger;

        $stkValOpeningStock = number_format($openingBalance * $openingStockRate, 2);

        $result = calculateStockBalance($openingBalance, $entries);
        if (!empty($result)) {
            $lastEntry = $result->last(); // Get the last element in the array
            // echo "Last Balance Value: " . ($lastEntry['Balance'] ?? 'N/A');
        } else {
            // echo "No valid data available.";
        }

        if (isset($lastEntry['Balance']) && isset($lastEntry['Val_of_Stock']))
        {
            $balanceAmount = (float) preg_replace('/[^\d.]/', '', $lastEntry['Balance'] ?? 0);
            $rateAmount = (float) preg_replace('/[^\d.]/', '', $lastEntry['Val_of_Stock'] ?? 0);
            $totalAvgWeight = number_format($rateAmount / $balanceAmount , 2);
        }
        else
        {
            $totalAvgWeight = 0;
        }

        $totalStockValue = $lastEntry['Val_of_Stock'] ?? 0;

        $totalStockInQuantity = calculateTotalStockInQuantity($openingBalance, $result);
        $totalStockInBags = calculateTotalStockInBags($result);
        $totalStockOutBags = calculateTotalStockOutBags($result);
        $totalStockOutQuantity = calculateTotalStockOutQuantity($result);
        // $avgInWeight = number_format($totalStockInQuantity / $totalStockInBags, 2);
        $avgInWeight = $totalStockInBags > 0 ? number_format($totalStockInQuantity / $totalStockInBags, 2): 0;
        // $avgOutWeight = number_format($totalStockOutQuantity  / $totalStockOutBags, 2);
        // $avgOutWeight = $totalStockOutQuantity != 0 ? number_format($totalStockOutQuantity / $totalStockOutBags, 2) : 0;
        $avgOutWeight = ($totalStockOutBags > 0) ? number_format($totalStockOutQuantity / $totalStockOutBags, 2) : 0;
    ?>

    <div class="container">
        <div class="header flex justify-between items-center p-4 border-b border-black">
            <div class="flex items-center">
                <img alt="Amir Foods logo with text 'Since 1996' and 'AMIR Food' in a shield-like shape" class="h-16" height="80" src="{{ asset('images/logo.png') }}" width="80"/>
                <div class="ml-4">
                    <p class="font-bold text-lg">AAMIR BROTHERS FOOD PRODUCTS MULTAN</p>
                    <p>12KM Vehari Road Multan <span class="font-bold">CELL:</span> 0309 6662476</p>
                    <p><span class="font-bold">EMAIL:</span> info.amirfoods@gmail.com</p>
                </div>
            </div>
            <div class="text-right">
                <p class="font-bold text-xl">{{$title}}</p>
            </div>
        </div>

        {{-- <header class="header"> --}}
            <div class="line"></div>
        {{-- </header> --}}

        <div class="info" style="margin-top: 1%;">
            <div class="row" style="margin-bottom: 3px;">

                <div style="width: 34%; text-align: left;">

                </div>
                <div style="width: 30%; text-align: left;">

                    <p><b>Opening Stock:</b> <span>
                        {{ $product->opening_stock }}
                    </span></p>
                </div>
            </div>

            <div class="row" style="margin-bottom: 3px;">

                <div style="width: 70%; text-align: left;">
                    <p><b>Catagory:</b> <span>
                        {{ $invMainHead }}
                    </span></p>
                    {{--  --}}

                </div>
                <div style="width: 30%; text-align: right;">
                    <p><b>Min Level:</b> <span>
                        {{ $product->min_limit }}
                    </span></p>
                </div>

            </div>
            <div class="row" style="margin-bottom: 3px;">
                <div style="width: 70%; text-align: left;">
                    <p><b>Item Code:</b> <span>
                        {{ $product->id }}
                    </span></p>

                </div>
                <div style="width: 30%; text-align: right;">
                    <p><b>Maximum Level:</b> <span>
                        {{ $product->max_limit }}
                    </span></p>

                </div>
            </div>
            <div class="row" style="margin-bottom: 3px;">
                <div style="width: 70%; text-align: left;">
                    <p><b>item Name:</b> <span>
                        {{ $product->name }}
                    </span></p>

                </div>
                <div style="width: 30%; text-align: right;">

                    <p><b>Danger Level:</b> <span>
                        {{ $product->danger_level }}
                    </span></p>
                </div>
            </div>
            <div class="row" style="margin-bottom: 3px;">
                <div style="width: 70%; text-align: left;">

                    <p><b>Use In:</b> <span>
                        {{ $product->use_in }}
                    </span></p>
                </div>

            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important; width: 13%;">Date</th>
                        {{-- <th style="text-align: center; padding: 0px 0px 0px 0px !important; width: 10%;">Description.</th> --}}
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important; width: 17%;">Supplier Name.</th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important; width: 8%;">Doc Name.</th>
                        <th colspan="3" style="text-align: center; padding: 0px 0px 0px 0px !important; width: 17%;">Stock In.</th>
                        <th colspan="3" style="text-align: center; padding: 0px 0px 0px 0px !important; width: 17%;">Stock Out</th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important; width: 7%;">Balance</th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important; width: 1%; border-color: white; border-right: black;"></th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important; width: 7%;">Rate</th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important; width: 13%;">Stock Val</th>
                    </tr>
                    <tr>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important;"></th>
                        {{-- <th style="text-align: center; padding: 0px 0px 0px 0px !important;"></th> --}}
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important;"></th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important;"></th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important;">Bags/<br>Units</th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important;">Avg<br> Wht</th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important;">Tot.Qty</th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important;">Bags/<br>Units</th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important;">Avg<br> Wht</th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important;">Tot.Qty</th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important;">Tot.Qty</th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important; width: 1%; border-color: white; border-right: black;"></th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important;"></th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important;"></th>
                    </tr>

                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        {{-- <td>{{ $product->name }}</td> --}}
                        <td></td>
                        <td></td>
                        <td style="text-align: end;">0</td>
                        <td style="text-align: end;">0</td>
                        <td style="text-align: end;">{{ $product->opening_stock }}</td>
                        <td style="text-align: end;">0</td>
                        <td style="text-align: end;">0</td>
                        <td style="text-align: end;">0</td>
                        <td style="text-align: end;">{{ $product->opening_stock }}</td>
                        <td style="text-align: center; padding: 0px 0px 0px 0px !important; width: 1%; border-color: white; border-right: black;"></td>
                        <td style="text-align: end;">{{$openingStockRate}}</td>
                        <td style="text-align: end;">{{$stkValOpeningStock}}</td>
                    </tr>

                    @foreach ($result as $stock)
                        <tr>

                            {{-- <td>{{ $stock->date }}</td> --}}
                            <td>{{ \Carbon\Carbon::parse($stock->date)->format('d-F-Y') }}</td>
                            {{-- <td>{{ $products[$stock->product_id] }}</td> --}}
                            <td>{{ $stock->party_title }}</td>
                            <td >{{ $stock->document_no }}</td>
                            <td style="text-align: end;">{{ $stock->stock_in_bags }}</td>
                            <td style="text-align: end;">{{ $stock->stock_in_weight }}</td>
                            <td style="text-align: end;">{{ $stock->stock_in_quantity }}</td>
                            <td style="text-align: end;">{{ $stock->stock_out_bags }}</td>
                            <td style="text-align: end;">{{ $stock->stock_out_weight }}</td>
                            <td style="text-align: end;">{{ $stock->stock_out_quantity }}</td>
                            <td style="text-align: end;">{{ $stock->Balance }}</td>
                            <td style="text-align: center; padding: 0px 0px 0px 0px !important; width: 1%; border-color: white; border-right: black;"> </td>
                            <td style="text-align: end;">{{ $stock->rate }}</td>
                            <td style="text-align: end;">{{ $stock->Val_of_Stock }}</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row" style="margin-top: 15px;">
                <div class="tableHeading" >
                    <div>
                        <h1 style="color: #762023; font-size: 35px; text-align: center; font-style: italic;"><b>STOCK SUMMARY</b></h1>
                    </div>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important; width: 7%; border-color: white; border-right: black;"></th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important; width: 15%; border-color: white; border-right: black;"></th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important; width: 19%;">Opening Stock</th>
                        <th colspan="3" style="text-align: center; padding: 0px 0px 0px 0px !important; width: 15%;">Stock In.</th>
                        <th colspan="3" style="text-align: center; padding: 0px 0px 0px 0px !important; width: 15%;">Stock Out</th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important; width: 8%;">Balance</th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important; width: 1%; border-color: white; border-right: black;"></th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important; width: 7%;">Avg Rate</th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important; width: 13%;">Stock Val</th>

                    </tr>

                    <tr>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important; border-color: white; border-right: black;"> </th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important; border-color: white; border-right: black;"> </th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important;"></th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important;">Bags/<br>Units</th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important;">Avg<br> Weight</th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important;">Tot.Qty</th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important;">Bags/<br>Units</th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important;">Avg<br> Weight</th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important;">Tot.Qty</th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important;">Bal.Qty</th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important; border-color: white; border-right: black;" ></th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important;"></th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important;"></th>
                    </tr>

                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center; padding: 0px 0px 0px 0px !important; width: 3%; border-color: white; border-right: black;"></td>
                        <td style="text-align: center; padding: 0px 0px 0px 0px !important; width: 3%; border-color: white; border-right: black;"></td>
                        <td style="text-align: center;">{{ $product->opening_stock }} </td>
                        <td style="text-align: end;">{{$totalStockInBags}}</td>
                        <td style="text-align: end;">{{$avgInWeight}}</td>
                        <td style="text-align: end;">{{$totalStockInQuantity}}</td>
                        <td style="text-align: end;">{{$totalStockOutBags}}</td>
                        <td style="text-align: end;">{{$avgOutWeight}}</td>
                        <td style="text-align: end;">{{$totalStockOutQuantity}}</td>
                        <td style="text-align: end;">{{$lastEntry['Balance'] ?? $product->opening_stock}}</td>
                        <td style="text-align: center; padding: 0px 0px 0px 0px !important; width: 1%; border-color: white; border-right: black;"></td>
                        <td style="text-align: end;">{{ $totalAvgWeight }}</td>
                        <td style="text-align: end;">{{$totalStockValue}}</td>

                    </tr>

                </tbody>
            </table>
        </div>

    </div>
</body>
</html>
