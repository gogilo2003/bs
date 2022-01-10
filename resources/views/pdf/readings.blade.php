<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Readings</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400&display=swap" rel="stylesheet">
    <style>
        body {
            font-size: 10pt;
            font-family: 'Montserrat', sans-serif;
        }

        {{ print file_get_contents(public_path('/css/table.css')) }}

        /* Striped table */
        .table-striped>tbody>tr:nth-child(odd)>* {
            background-color: rgba(0, 0, 0, .05);
            color: #212529;
        }

    </style>
</head>

<body>
    <table class="table table-striped table-bordered" style="width: auto">
        <thead>
            <tr>
                <th></th>
                <th width="180">DATE</th>
                <th>TIME</th>
                <th>TYPE</th>
                <th>READING</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($readings as $reading)
                <tr style="background-color:{{ $loop->iteration % 2 == 1 ? 'rgba(0, 0, 0, .05)' : '#fff' }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ (new DateTime($reading->read_at))->format('D j-M-Y') }}</td>
                    <td>{{ (new DateTime($reading->read_at))->format('h:iA') }}</td>
                    <td>{{ strtoupper($reading->type) }}</td>
                    <td>{{ $reading->reading }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
