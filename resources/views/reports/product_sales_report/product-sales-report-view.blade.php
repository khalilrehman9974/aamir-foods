<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
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
            @page {
                size: A4 portrait;
                margin-top: 10mm;
                /* ✅ This sets the actual top margin on the printed page */
                margin-left: 10mm;
                margin-right: 10mm;
                margin-bottom: 10mm;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100vw;
                height: 100vh;
                font-size: 12px;
                text-align: left !important;
                overflow: visible !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                background-color: white;
            }

            .container {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                page-break-after: auto;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
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

            p,
            td,
            th {
                font-size: 12px;
            }

            .print-bg {
                background-color: #f0f0f0 !important;
                color: #000;
                padding: 10px;
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
            border: 1px solid lightgray !important;
            padding: 2px;
            text-align: left;
        }

        .notes {
            border: 1px solid lightgray !important;
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

        .p-4 {
            padding: 0px !important;
        }

        .totals th,
        .totals td {
            border: 1px lightgray !important;
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
    <div class="container">
        <div class="header flex justify-between items-center p-4 border-b border-black">
            <div class="flex items-center">
                <img alt="Amir Foods logo with text 'Since 1996' and 'AMIR Food' in a shield-like shape" class="h-16"
                    height="80" src="{{ asset('images/logo.png') }}" width="80" />
                <div class="ml-4">
                    <p class="font-bold text-lg">AAMIR BROTHERS FOOD PRODUCTS MULTAN</p>
                    <p>12KM Vehari Road Multan <span class="font-bold">CELL:</span> 0309 6662476</p>
                    <p><span class="font-bold">EMAIL:</span> info.amirfoods@gmail.com</p>
                </div>
            </div>
            <div class="text-right">
                {{-- <p>ABF: S/M-01</p> --}}
                <p class="font-bold text-xl">Product Sale Report</p>
            </div>
        </div>

        {{-- <header class="header"> --}}
        <div class="line"></div>
        {{-- </header> --}}

        <div class="info" style="margin-top: 1%;">
            {{-- <div class="row" style="margin-bottom: 3px;">
                <div style="width: 70%; text-align: left;">
                    <p><b>Product:</b>
                        <span>
                            {{ !empty($productId) ? $products[$productId] ?? 'Unknown Product' : 'Multiple' }}
                        </span>
                    </p>
                </div>

            </div> --}}

            <div class="row" style="margin-bottom: 3px;">
                <div style="width: 70%; text-align: left;">
                    <p><b>From Date:</b> <span>{{ \Carbon\Carbon::parse($fromDate)->format('d-F-Y') }}</span></p>
                </div>
                <div style="width: 30%; text-align: right;">
                    <p><b>To Date:</b> <span>{{ \Carbon\Carbon::parse($toDate)->format('d-F-Y') }}</span></p>
                </div>
            </div>

        </div>

        <div class="table-container">
            <table>
                <thead class="bg-gray-200">
                    <tr>
                        <th style="width: 3%;">Sr.#</th>
                        <th style="width: 30%;">Product</th>
                        <th style="width: 8%;">Quantity</th>
                        <th style="width: 10%;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productTotals as $summary)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $summary['name'] }}</td>
                            <td>{{ $summary['quantity'] }}</td>
                            <td>{{ number_format($summary['amount'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="row" style="display: flex;">
            <div class="QWER" style="width: 64.5%;">

            </div>
            <div class="table-container" style="width: 35.5%;">
                <table>
                    <tbody>
                        <tr>
                            <td style="width: 45%"><strong>Grand Total</strong></td>
                            <td style="width: 55%"><strong>{{ number_format($totalAmount, 2) }}</strong></td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

        <header class="header">
            <div class="line"></div>
        </header>

        <div class="signatures flex justify-between items-center mt-4">
            <div class="flex items-center">
                <p class="text-black mr-2"><b>Created By:</b></p>
                <div class="w-32 h-4 border-b-2 border-black" style="margin-left: 5px;">{{ $user }}</div>
            </div>
            <div class="flex items-center">
                <p class="text-black mr-2"><b>Signed & Approved By:</b></p>
                <div class="w-32 h-4 border-b-2 border-black"></div>
            </div>
        </div>

        <div class="contact mt-4">
            <p>If you have any questions about this Document, Please contact</p>
            <p><b>Phone:</b> 0309 6662476 <b>Email:</b> info.amirfoods@gmail.com</p>
        </div>
    </div>
</body>

</html>
