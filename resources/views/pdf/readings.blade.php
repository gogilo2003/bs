<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Readings</title>
    <style>
        {!! file_get_contents(public_path('css/print.css')) !!}

    </style>
</head>

<body>
    <h3>Blood Sugar readings</h3>

    <table class="table table-striped table-bordered" style="width: auto">
        <thead>
            <tr>
                <th>#</th>
                <th>DATE</th>
                <th>TIME</th>
                <th>TYPE</th>
                <th>READING</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($readings as $reading)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ (new DateTime($reading->read_at))->format('D j-M-Y') }}</td>
                    <td>{{ (new DateTime($reading->read_at))->format('h:iA') }}</td>
                    <td>{{ strtoupper($reading->type) }}</td>
                    <td style="text-align: right">{{ $reading->reading }}</td>
                </tr>
            @empty
                <tr>
                    <td style="width:95mm; text-align: center" colspan="5">No Data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
