<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: calibary, sans-serif;
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
            border: 1px solid lightgray;
            padding: 8px;
            text-align: left;
        }

        .notes {
            border: 1px solid lightgray;
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
            border: 1px solid lightgray;
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
                <p class="font-bold text-xl">{{ $title }}</p>
            </div>
        </div>

        {{-- <header class="header"> --}}
        <div class="line"></div>
        {{-- </header> --}}
        <div class="info" style="margin-top: 1%;">
            <div class="row" style="margin-bottom: 3px;">
                <div style="width: 70%; text-align: left;">
                    <p><b>Run Period:</b><br> <span>
                        <b>From </b>{{ \Carbon\Carbon::parse($dateFrom?? null)->format('d-F-Y') }} <b> To </b>{{ \Carbon\Carbon::parse($dateTo?? null)->format('d-F-Y') }}
                        </span></p>
                </div>
            </div>


        </div>

        <div class="table-container">
            <table>
                <thead>

                    <tr>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important; width: 10%;">Date</th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important; width: 10%;">DOC#</th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important; width: 20%;">DESCRIPTION</th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important; width: 40%;">NARRATION</th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important; width: 10%;">DEBIT</th>
                        <th style="text-align: center; padding: 0px 0px 0px 0px !important; width: 10%;">CREDIT</th>

                    </tr>

                </thead>
                <tbody>

                    @foreach ($generalJournals as $generalJournal)
                        <tr>

                            <td>{{ \Carbon\Carbon::parse($generalJournal->date)->format('d-F-Y') }}</td>
                            <td>{{ $generalJournal->document_number }}</td>
                            <td>{{ $generalJournal->description }}</td>
                            <td>{{ $generalJournal->narration }}</td>
                            <td style="text-align: end;">{{ number_format($generalJournal->debit ?? 0, 2) }}</td>
                            <td style="text-align: end;">{{ number_format($generalJournal->credit ?? 0, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
        <header class="header">
            <div class="line"></div>
        </header>


    </div>
</body>

</html>
