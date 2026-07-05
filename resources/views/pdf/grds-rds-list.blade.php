<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>GRDS/RDS List</title>

    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th {
            background: #16a34a;
            color: white;
            padding: 10px;
            text-transform: uppercase;
        }

        td {
            padding: 8px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div style="display: flex; justify-content: center; width: 100%;">
        <img src="{{ public_path('images/TranscoLogo.png') }}" alt="TranscoLogo" style="width: 100px;">
    <p style="color: red; margin: 5px 0; font-weight:bold; font-size:x-large;">National Transmission Corporation</p>
    </div>
    
    <h1 class="uppercase bold" style="font-size: 16px; margin: 10px 0; text-align: center;">GRDS/RDS Lists</h1>
    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>GRDS/RDS No</th>
                <th>Retention Period</th>
                <th>Document Status</th>
            </tr>
        </thead>

        <tbody>
            @foreach($list as $item)
            <tr>
                <td>{{ $item->description }}</td>
                <td>{{ $item->grds_rds_no }}</td>
                <td>{{ $item->retention_period }}</td>
                <td>{{ $item->document_status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>