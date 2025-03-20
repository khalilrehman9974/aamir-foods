<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
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
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        .notes {
            border: 1px solid black;
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
            border: 1px solid black;
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
                <img alt="Amir Foods logo with text 'Since 1996' and 'AMIR Food' in a shield-like shape" class="h-16" height="80" src="{{ asset('images/logo.png') }}" width="80"/>
                <div class="ml-4">
                    <p class="font-bold text-lg">AAMIR BROTHERS FOOD PRODUCTS MULTAN</p>
                    <p>12KM Vehari Road Multan <span class="font-bold">CELL:</span> 0309 6662476</p>
                    <p><span class="font-bold">EMAIL:</span> info.amirfoods@gmail.com</p>
                </div>
            </div>
            <div class="text-right">
                <p>ABF: Store-Issue-Note-01</p>
                <p class="font-bold text-xl">{{$title}}</p>
            </div>
        </div>

        {{-- <header class="header"> --}}
            <div class="line"></div>
        {{-- </header> --}}

        <div class="info" style="margin-top: 1%;">
            <div class="row" style="margin-bottom: 3px;">
                <div style="width: 70%; text-align: left;">
                    <p><b>Store Issue Note#:</b> <span>{{ $issueNoteMaster->id }}</span></p>
                </div>
                <div style="width: 30%; text-align: right;">
                    <p><b> Date:</b> <span>{{ $date }}</span></p>
                </div>
            </div>

            <div class="row" style="margin-bottom: 3px;">
                <div style="width: 70%; text-align: left;">
                    <p><b>Receiver Name:</b> <span>{{ $issueNoteMaster->receiver_name }}</span></p>
                </div>
                <div style="width: 30%; text-align: right;">
                    <p><b>From Department:</b> <span>{{ $fromDepartment }}</span></p>
                </div>
            </div>
            <div class="row" style="margin-bottom: 3px;">
                <div style="width: 70%; text-align: left;">
                    <p><b>To Department:</b> <span>{{ $toDepartment }}</span></p>
                </div>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 3%;">Sr.#</th>
                        <th style="width: 30%;">Product</th>
                        <th style="width: 8%;">P/T</th>
                        <th style="width: 8%;">M/T</th>
                        <th style="width: 8%;">Size</th>
                        <th style="width: 10%;">Bags/Units</th>
                        <th style="width: 5%;">Avg Weight</th>
                        <th style="width: 5%;">Tot.Qty</th>
                        <th style="width: 20%;">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($issueNoteDetails as $issueNoteDetail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $products[$issueNoteDetail->product_id] }}</td>
                            <td>{{ $issueNoteDetail->packing_type }}</td>
                            <td>{{ $issueNoteDetail->measurement_type }}</td>
                            <td>{{ $issueNoteDetail->size }}</td>
                            <td>{{ $issueNoteDetail->bags }}</td>
                            <td>{{ $issueNoteDetail->avg_weight }}</td>
                            <td>{{ $issueNoteDetail->total_qty }}</td>
                            <td>{{ $issueNoteDetail->remarks }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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

        {{-- <div class="contact mt-4">
            <p>If you have any questions about this Document, Please contact</p>
            <p><b>Phone:</b> 0309 6662476 <b>Email:</b> info.amirfoods@gmail.com</p>
        </div> --}}
    </div>
</body>
</html>
