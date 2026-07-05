<!DOCTYPE html>
<html>

<head>2
    <title>{{ $inventory->prepared_by }} - RTO Inventory PDF</title>
    <style>
        body {
            font-family: "Nunito", sans-serif;
            font-size: 12px;
            margin: 20px;
        }


        table {
            width: 100%;
            border-collapse: collapse;

        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .center {
            text-align: center;
        }

        .br {
            margin: 10px 0;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .foot {
            text-transform: uppercase;

        }

        .bold {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <!-- Info Section -->
    <table style="width: 100%;">
        <tr>
            <td style="width: 48%; vertical-align: top; border: none; margin-left: 20px;">
                <img src="{{ public_path('images/TranscoLogo.png') }}" alt="TranscoLogo" style="width: 100px;">
                <p style="color: red; margin: 5px 0; font-weight:bold; font-size:x-large;">National Transmission Corporation</p>
            </td>
            <td style="width: 48%; vertical-align: top; border: none;">
                <h1 class="uppercase bold" style="font-size: 16px; margin: 10px 0;">RECORDS TURN-OVER / INVENTORY LIST FORM</h1>
            </td>
        </tr>
    </table>
    <!-- <h4>Inventory Id: {{ $inventory->id }}</h4> -->
    <table style="width: 100%;">
        <tr>
            <td style="width: 48%; vertical-align: top;">
                <p class="uppercase"><strong>Office Origin:</strong> <span style="text-transform: capitalize;">{{ $inventory->office_origin}}</span></p>
                <p class="uppercase"><strong>Turn-Over Date:</strong> <span style="text-transform: capitalize;">{{ \Carbon\Carbon::parse($inventory->created_at)->format('m/d/Y') }}</span></p>
            </td>
            <td style="width: 48%; vertical-align: top;">
                <p class="uppercase">
                    <strong>Prepared/Turn-over By:</strong>
                    <span style="text-transform: capitalize;">
                        {{ $inventory->prepared_by}}
                    </span>

                    <span>
                        @if ($inventory->verified_by && $prepared_by?->signature)
                        <img
                            src="{{ public_path('storage/' . $prepared_by->signature) }}"
                            alt="Signature"
                            style="
                                    width:60px;
                                    height:auto;
                                    vertical-align:middle;
                                    margin-left:10px;
                                ">
                        <span
                            style="
                                display:inline-block;
                                font-size:9px;
                                line-height:1.2;
                                vertical-align:middle;
                                margin-left:6px;
                                text-transform:none;
                            ">
                            <strong>Digitally signed by</strong><br><span style="text-transform: capitalize;">{{ $inventory->prepared_by }}</span>
                            <br>
                            Date: {{ \Carbon\Carbon::parse($inventory->created_at)->format('m/d/Y h:i A') }}
                        </span>
                        @endif
                    </span>
                </p>

                <p class="uppercase">
                    <strong>Approved By:</strong>
                    <span style="text-transform: capitalize;">
                        {{ $inventory->manager_approval }}
                    </span>
                    @if ($inventory->verified_by && $manager_approval?->signature)
                    <img
                        src="{{ public_path('storage/' . $manager_approval->signature) }}"
                        alt="Signature"
                        style="
                                width:60px;
                                height:auto;
                                vertical-align:middle;
                                margin-left:10px;
                                z-index: 0;
                            ">
                    <span
                        style="
                                display:inline-block;
                                font-size:9px;
                                line-height:1.2;
                                vertical-align:middle;
                                margin-left:6px;
                                text-transform:none;
                            ">
                        <strong>Digitally signed by</strong><br><span style="text-transform: capitalize;">{{ $inventory->manager_approval }}</span>
                        <br>
                        Date: {{ \Carbon\Carbon::parse($inventory->manager_approval_date)->format('m/d/Y h:i A') }}
                    </span>
                    @endif
                </p>
            </td>
        </tr>
    </table>

    <!-- Table Section -->
    <table>
        <thead>
            <tr>
                <th>Item No</th>
                <th class="uppercase">Document Description</th>
                <th class="uppercase">Doc Date</th>
                <th class="uppercase">Quantity</th>
                <th class="uppercase">Unit Code</th>
                <th class="uppercase">Document Status</th>
                <th class="uppercase">RDS Series No.</th>
                <th class="uppercase">Retention Period</th>
                <th class="uppercase">Disposal Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($inventory->items as $item)
            @php
            $disposalDate = \Carbon\Carbon::parse($item->disposal_date);
            $now = \Carbon\Carbon::now();

            $diffInYears = $disposalDate->year - $now->year;
            $color = '';

            if ($diffInYears === 1) {
            $color = '#92400e';
            } elseif ($diffInYears >= 2) {
            $color = '#16a34a';
            } elseif ($diffInYears <= 0) {
                $color='#dc2626' ;
                }
                @endphp
                <tr>
                <td>{{ $item->item_no }}</td>
                <td>{{ $item->description }}</td>
                <td>{{ \Carbon\Carbon::parse($item->doc_date)->format('m/d/Y') }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->unit_code }}</td>
                <td>
                    {{ $item->document_status }}
                </td>
                <td>{{ $item->rds_no }}</td>
                <td>
                    @if ($item->retention_period)
                    {{ $item->retention_period }}
                    <span style="margin-right: 2px; text-transform:uppercase;">year/s</span>
                    @else
                    —
                    @endif
                </td>

                <td style="color: {{ $color }}">
                    {{ $item->disposal_date ? \Carbon\Carbon::parse($item->disposal_date)->format('m/d/Y') : '—' }}
                </td>
                </tr>
                @endforeach
        </tbody>
    </table>
    <div class="br">
        <div>-----------------------------------------------------------------------------------------------<span style="font-style: italic;">TO BE FILLED BY RECORDS PERSONNEL</span>---------------------------------------------------------------------------------------------</div>
    </div>
    <table style="width: 100%;">
        <tr>
            <td style="width: 48%; vertical-align: top; border: none; margin-left: 20px;">
                <p class="foot"><strong>BOX NO.:</strong> {{ $inventory->id }}</p>
                <p class="foot"><strong>NAP Authority No.:</strong> {{ $inventory->nap_authority_no }}</p>
                <p class="foot"><strong>LOC CODE:</strong> {{ $inventory->loc_code }}</p>
            </td>
            <td style="width: 48%; vertical-align: top; border: none; margin-left: 20px;">
                <p class="foot"><strong>Rack No.:</strong> {{ $inventory->rack_no }}</p>
            </td>
            <td style="width: 48%; vertical-align: top; border: none;">
                <p class="foot"><strong>received by: </strong> {{ $inventory->received_by}}</p>
                <p class="foot"><strong>Date: </strong>
                    @if(!empty($inventory->received_by))
                    {{ \Carbon\Carbon::parse($inventory->received_date)->format('m/d/Y') }}
                    @endif
                </p>
                <p class="foot"><strong>Validated by(supervisor):</strong> {{ $inventory->verified_by}}</p>
                <p class="foot"><strong>date: </strong>
                    @if(!empty($inventory->verified_by))
                    {{ \Carbon\Carbon::parse($inventory->verified_date)->format('m/d/Y') }}
                    @endif
                </p>
                @if ($inventory->verified_by && $verified_by?->signature)
                <img
                    src="{{ public_path('storage/' . $verified_by->signature) }}"
                    alt="Signature"
                    style="
                                width:60px;
                                height:auto;
                                vertical-align:middle;
                                margin-left:10px;
                            ">
                <span
                    style="
                                display:inline-block;
                                font-size:9px;
                                line-height:1.2;
                                vertical-align:middle;
                                margin-left:6px;
                                text-transform:none;
                            ">
                    <strong>Digitally signed by</strong><br><span style="text-transform: capitalize;">{{ $inventory->verified_by }}</span>
                    <br>
                    Date: {{ \Carbon\Carbon::parse($inventory->veridied_date)->format('m/d/Y h:i A') }}
                </span>
                @endif
            </td>
        </tr>
    </table>
</body>

</html>