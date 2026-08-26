<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Sugar Readings Report</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }

        body {
            font-family: 'Figtree', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #111827;
            background: #ffffff;
            font-size: 11px;
        }

        .header {
            text-align: center;
            margin-bottom: 12px;
        }

        .header h1 {
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 1px;
            margin: 0 0 4px 0;
            text-transform: uppercase;
        }

        .header h2 {
            font-size: 10px;
            font-weight: 600;
            color: #4b5563;
            letter-spacing: 0.5px;
            margin: 0;
            text-transform: uppercase;
        }

        .chart-container {
            width: 100%;
            margin-bottom: 16px;
            text-align: center;
        }

        .content-layout {
            width: 100%;
        }

        .table-col {
            width: 68%;
            vertical-align: top;
            padding-right: 15px;
        }

        .summary-col {
            width: 32%;
            vertical-align: top;
        }

        .section-title {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
            color: #111827;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #A6A6A6;
            font-size: 9.5px;
        }

        table.data-table th {
            background-color: #A6A6A6;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            padding: 4px 6px;
            border: 1px solid #A6A6A6;
            text-align: left;
        }

        table.data-table th.center,
        table.data-table td.center {
            text-align: center;
        }

        table.data-table th.right,
        table.data-table td.right {
            text-align: right;
        }

        table.data-table td {
            padding: 3px 6px;
            border: 1px solid #A6A6A6;
        }

        table.data-table tr.odd {
            background-color: #F0EFEF;
        }

        table.data-table tr.even {
            background-color: #ffffff;
        }

        table.summary-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #A6A6A6;
            margin-bottom: 12px;
            font-size: 9.5px;
        }

        table.summary-table td {
            padding: 3px 6px;
            border: 1px solid #A6A6A6;
        }

        table.summary-table td.label {
            width: 50%;
            text-transform: uppercase;
            font-weight: 500;
            color: #374151;
            border-right: 1px solid #A6A6A6;
        }

        table.summary-table td.val {
            text-align: right;
            font-weight: 600;
        }
    </style>
</head>

<body>
    @php
        $reportTitle = match($type ?? 'all') {
            'today' => "TODAY'S REPORT (" . strtoupper(date('d F Y')) . ")",
            'week' => "WEEKLY REPORT (" . strtoupper(date('F Y')) . ")",
            'month' => "MONTHLY REPORT(" . strtoupper(date('F Y')) . ")",
            'quarterly' => "QUARTERLY REPORT (" . strtoupper(date('F Y')) . ")",
            default => "ALL TIME REPORT (" . strtoupper(date('F Y')) . ")",
        };

        // Prepare chart values
        $chartDates = !empty($last7DaysReadings) ? array_keys($last7DaysReadings) : [];
        sort($chartDates);
        $numDates = count($chartDates);
        $svgWidth = 520;
        $svgHeight = 130;
        $padLeft = 35;
        $padRight = 15;
        $padTop = 25;
        $padBottom = 25;
        $plotW = $svgWidth - $padLeft - $padRight;
        $plotH = $svgHeight - $padTop - $padBottom;

        $allVals = [];
        foreach ($chartDates as $d) {
            foreach ($last7DaysReadings[$d] as $item) {
                if (isset($item['mean_reading']) && is_numeric($item['mean_reading'])) {
                    $allVals[] = (float)$item['mean_reading'];
                }
            }
        }
        $minVal = !empty($allVals) ? max(0, floor(min($allVals)) - 1) : 4;
        $maxVal = !empty($allVals) ? ceil(max($allVals)) + 1 : 16;
        if ($maxVal <= $minVal) { $maxVal = $minVal + 10; }
        $range = $maxVal - $minVal;

        $fbsPoints = [];
        $rbsPoints = [];

        foreach ($chartDates as $i => $d) {
            $x = $numDates > 1 ? $padLeft + ($i * ($plotW / ($numDates - 1))) : $padLeft + ($plotW / 2);
            $items = $last7DaysReadings[$d] ?? [];
            foreach ($items as $item) {
                if (($item['type'] ?? '') === 'fbs' && isset($item['mean_reading']) && is_numeric($item['mean_reading'])) {
                    $y = $padTop + $plotH - ((($item['mean_reading'] - $minVal) / $range) * $plotH);
                    $fbsPoints[] = [$x, $y];
                }
                if (($item['type'] ?? '') === 'rbs' && isset($item['mean_reading']) && is_numeric($item['mean_reading'])) {
                    $y = $padTop + $plotH - ((($item['mean_reading'] - $minVal) / $range) * $plotH);
                    $rbsPoints[] = [$x, $y];
                }
            }
        }

        $buildSvgPath = function($pts) {
            if (empty($pts)) return '';
            $d = "M " . $pts[0][0] . " " . $pts[0][1];
            for ($i = 1; $i < count($pts); $i++) {
                $prev = $pts[$i - 1];
                $curr = $pts[$i];
                $cx = ($prev[0] + $curr[0]) / 2;
                $d .= " C $cx {$prev[1]}, $cx {$curr[1]}, {$curr[0]} {$curr[1]}";
            }
            return $d;
        };

        $fbsPath = $buildSvgPath($fbsPoints);
        $rbsPath = $buildSvgPath($rbsPoints);
    @endphp

    <div class="header">
        <h1>BLOOD SUGAR READINGS REPORT</h1>
        <h2>{{ $reportTitle }}</h2>
    </div>

    @if ($numDates > 0)
    <div class="chart-container">
        <svg width="100%" height="130" viewBox="0 0 {{ $svgWidth }} {{ $svgHeight }}" style="background: #ffffff;">
            <!-- Legend -->
            <rect x="{{ $padLeft }}" y="6" width="12" height="6" fill="#9333EA" />
            <text x="{{ $padLeft + 16 }}" y="12" font-size="8" fill="#374151" font-family="sans-serif">Fasting Blood Sugar Readings</text>

            <rect x="{{ $padLeft + 150 }}" y="6" width="12" height="6" fill="#F97316" />
            <text x="{{ $padLeft + 166 }}" y="12" font-size="8" fill="#374151" font-family="sans-serif">Random Blood Sugar Readings</text>

            <!-- Y Grid Lines & Ticks -->
            @for ($tick = 0; $tick <= 4; $tick++)
                @php
                    $v = round($minVal + ($tick * ($range / 4)), 1);
                    $yPos = $padTop + $plotH - ($tick * ($plotH / 4));
                @endphp
                <line x1="{{ $padLeft }}" y1="{{ $yPos }}" x2="{{ $svgWidth - $padRight }}" y2="{{ $yPos }}" stroke="#E5E7EB" stroke-width="1" />
                <text x="{{ $padLeft - 5 }}" y="{{ $yPos + 3 }}" font-size="8" fill="#6B7280" text-anchor="end" font-family="sans-serif">{{ $v }}</text>
            @endfor

            <!-- X Ticks -->
            @foreach ($chartDates as $i => $d)
                @php
                    $xPos = $numDates > 1 ? $padLeft + ($i * ($plotW / ($numDates - 1))) : $padLeft + ($plotW / 2);
                @endphp
                <line x1="{{ $xPos }}" y1="{{ $padTop }}" x2="{{ $xPos }}" y2="{{ $padTop + $plotH }}" stroke="#F3F4F6" stroke-width="1" />
                <text x="{{ $xPos }}" y="{{ $padTop + $plotH + 14 }}" font-size="7" fill="#6B7280" text-anchor="middle" font-family="sans-serif">{{ date('D, jS M', strtotime($d)) }}</text>
            @endforeach

            <!-- Lines -->
            @if (!empty($fbsPath))
                <path d="{{ $fbsPath }}" fill="none" stroke="#9333EA" stroke-width="2" />
                @foreach ($fbsPoints as $pt)
                    <circle cx="{{ $pt[0] }}" cy="{{ $pt[1] }}" r="2" fill="#9333EA" />
                @endforeach
            @endif

            @if (!empty($rbsPath))
                <path d="{{ $rbsPath }}" fill="none" stroke="#F97316" stroke-width="2" />
                @foreach ($rbsPoints as $pt)
                    <circle cx="{{ $pt[0] }}" cy="{{ $pt[1] }}" r="2" fill="#F97316" />
                @endforeach
            @endif
        </svg>
    </div>
    @endif

    <table class="content-layout">
        <tr>
            <td class="table-col">
                <div class="section-title">READINGS</div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="center" style="width: 25px">#</th>
                            <th style="width: 100px">DATE</th>
                            <th style="width: 65px">TIME</th>
                            <th style="width: 50px">TYPE</th>
                            <th class="right" style="width: 60px">READING</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($readings as $reading)
                            <tr class="{{ $loop->iteration % 2 === 1 ? 'odd' : 'even' }}">
                                <td class="center">{{ $loop->iteration }}</td>
                                <td>{{ (new DateTime($reading->read_at))->format('D d-M-Y') }}</td>
                                <td>{{ (new DateTime($reading->read_at))->format('h:iA') }}</td>
                                <td>{{ strtoupper($reading->type) }}</td>
                                <td class="right">{{ number_format((float)$reading->reading, 1) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="center" style="padding: 16px; color: #6b7280; background: #ffffff;">
                                    No readings recorded.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </td>

            <td class="summary-col">
                <div class="section-title">WEEKLY SUMMARY</div>
                <table class="summary-table">
                    <tr><td class="label">MEAN</td><td class="val">{{ number_format((float)($weeklyStats['mean'] ?? 0), 1) }}</td></tr>
                    <tr><td class="label">MIN</td><td class="val">{{ number_format((float)($weeklyStats['min'] ?? 0), 1) }}</td></tr>
                    <tr><td class="label">MAX</td><td class="val">{{ number_format((float)($weeklyStats['max'] ?? 0), 1) }}</td></tr>
                </table>

                <div class="section-title">MONTHLY SUMMARY</div>
                <table class="summary-table">
                    <tr><td class="label">MEAN</td><td class="val">{{ number_format((float)($monthlyStats['mean'] ?? 0), 1) }}</td></tr>
                    <tr><td class="label">MIN</td><td class="val">{{ number_format((float)($monthlyStats['min'] ?? 0), 1) }}</td></tr>
                    <tr><td class="label">MAX</td><td class="val">{{ number_format((float)($monthlyStats['max'] ?? 0), 1) }}</td></tr>
                </table>

                <div class="section-title">QUARTERLY SUMMARY</div>
                <table class="summary-table">
                    <tr><td class="label">MEAN</td><td class="val">{{ number_format((float)($quarterlyStats['mean'] ?? 0), 1) }}</td></tr>
                    <tr><td class="label">MIN</td><td class="val">{{ number_format((float)($quarterlyStats['min'] ?? 0), 1) }}</td></tr>
                    <tr><td class="label">MAX</td><td class="val">{{ number_format((float)($quarterlyStats['max'] ?? 0), 1) }}</td></tr>
                </table>

                <div class="section-title">ALL TIME SUMMARY</div>
                <table class="summary-table">
                    <tr><td class="label">MEAN</td><td class="val">{{ number_format((float)($allTimeStats['mean'] ?? 0), 1) }}</td></tr>
                    <tr><td class="label">MIN</td><td class="val">{{ number_format((float)($allTimeStats['min'] ?? 0), 1) }}</td></tr>
                    <tr><td class="label">MAX</td><td class="val">{{ number_format((float)($allTimeStats['max'] ?? 0), 1) }}</td></tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
