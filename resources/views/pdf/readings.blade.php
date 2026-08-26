<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Blood Sugar Readings Report</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 14mm 12mm 14mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #000000;
            background: #ffffff;
            font-size: 11px;
            -webkit-print-color-adjust: exact;
        }

        .header {
            text-align: center;
            margin-bottom: 14px;
        }

        .header h1 {
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 0.8px;
            margin: 0 0 3px 0;
            text-transform: uppercase;
            color: #000000;
        }

        .header h2 {
            font-size: 9.5px;
            font-weight: 500;
            color: #1f2937;
            letter-spacing: 0.4px;
            margin: 0;
            text-transform: uppercase;
        }

        .chart-container {
            width: 100%;
            margin-bottom: 16px;
            text-align: center;
        }

        .columns-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }

        .col-readings {
            width: 68%;
            vertical-align: top;
            padding-right: 20px;
        }

        .col-summary {
            width: 32%;
            vertical-align: top;
        }

        .section-heading {
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
            color: #000000;
        }

        table.readings-grid {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #A6A6A6;
            font-size: 8.5px;
        }

        table.readings-grid th {
            background-color: #A6A6A6;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            padding: 4px 6px;
            border: 1px solid #A6A6A6;
            text-align: left;
            height: 20px;
        }

        table.readings-grid th.center,
        table.readings-grid td.center {
            text-align: center;
        }

        table.readings-grid th.right,
        table.readings-grid td.right {
            text-align: right;
        }

        table.readings-grid td {
            padding: 3px 6px;
            border: 1px solid #A6A6A6;
            height: 19px;
            color: #000000;
        }

        table.readings-grid tr.row-odd {
            background-color: #F0EFEF;
        }

        table.readings-grid tr.row-even {
            background-color: #ffffff;
        }

        table.summary-grid {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #A6A6A6;
            margin-bottom: 14px;
            font-size: 8.5px;
            background: #ffffff;
        }

        table.summary-grid td {
            padding: 3px 8px;
            border: 1px solid #A6A6A6;
            height: 18px;
            color: #000000;
        }

        table.summary-grid td.label-cell {
            width: 50%;
            text-transform: uppercase;
            font-weight: 500;
            border-right: 1px solid #A6A6A6;
        }

        table.summary-grid td.val-cell {
            text-align: right;
            font-weight: 500;
        }
    </style>
</head>

<body>
    @php
        $reportTitle = match($type ?? 'all') {
            'today' => "TODAY'S REPORT(" . strtoupper(date('d F Y')) . ")",
            'week' => "WEEKLY REPORT(" . strtoupper(date('F Y')) . ")",
            'month' => "MONTHLY REPORT(" . strtoupper(date('F Y')) . ")",
            'quarterly' => "QUARTERLY REPORT(" . strtoupper(date('F Y')) . ")",
            default => "ALL TIME REPORT(" . strtoupper(date('F Y')) . ")",
        };

        // Prepare chart values
        $chartDates = !empty($last7DaysReadings) ? array_keys($last7DaysReadings) : [];
        sort($chartDates);
        $numDates = count($chartDates);

        $svgWidth = 530;
        $svgHeight = 145;
        $padLeft = 32;
        $padRight = 12;
        $padTop = 22;
        $padBottom = 22;
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
        $minVal = !empty($allVals) ? max(0, floor(min($allVals)) - 1) : 6;
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
        <svg width="100%" height="145" viewBox="0 0 {{ $svgWidth }} {{ $svgHeight }}" style="background: #ffffff;">
            <!-- Legend -->
            <rect x="{{ $padLeft + 40 }}" y="5" width="14" height="6" fill="#9333EA" />
            <text x="{{ $padLeft + 58 }}" y="11" font-size="7.5" fill="#374151" font-family="sans-serif">Fasting Blood Sugar Readings</text>

            <rect x="{{ $padLeft + 200 }}" y="5" width="14" height="6" fill="#F97316" />
            <text x="{{ $padLeft + 218 }}" y="11" font-size="7.5" fill="#374151" font-family="sans-serif">Random Blood Sugar Readings</text>

            <!-- Chart Box Border -->
            <rect x="{{ $padLeft }}" y="{{ $padTop }}" width="{{ $plotW }}" height="{{ $plotH }}" fill="none" stroke="#A6A6A6" stroke-width="1" />

            <!-- Y Grid Lines & Ticks -->
            @for ($tick = 1; $tick < 5; $tick++)
                @php
                    $yPos = $padTop + ($tick * ($plotH / 5));
                @endphp
                <line x1="{{ $padLeft }}" y1="{{ $yPos }}" x2="{{ $padLeft + $plotW }}" y2="{{ $yPos }}" stroke="#E5E7EB" stroke-width="1" />
            @endfor

            @for ($tick = 0; $tick <= 5; $tick++)
                @php
                    $v = round($minVal + ($tick * ($range / 5)), 1);
                    $yPos = $padTop + $plotH - ($tick * ($plotH / 5));
                @endphp
                <text x="{{ $padLeft - 5 }}" y="{{ $yPos + 2.5 }}" font-size="7.5" fill="#374151" text-anchor="end" font-family="sans-serif">{{ $v }}</text>
            @endfor

            <!-- X Vertical Grid Lines & Ticks -->
            @foreach ($chartDates as $i => $d)
                @php
                    $xPos = $numDates > 1 ? $padLeft + ($i * ($plotW / ($numDates - 1))) : $padLeft + ($plotW / 2);
                @endphp
                @if ($i > 0 && $i < $numDates - 1)
                    <line x1="{{ $xPos }}" y1="{{ $padTop }}" x2="{{ $xPos }}" y2="{{ $padTop + $plotH }}" stroke="#E5E7EB" stroke-width="1" />
                @endif
                <text x="{{ $xPos }}" y="{{ $padTop + $plotH + 12 }}" font-size="6.5" fill="#374151" text-anchor="middle" font-family="sans-serif">{{ date('D, jS M, Y', strtotime($d)) }}</text>
            @endforeach

            <!-- Spline Lines -->
            @if (!empty($fbsPath))
                <path d="{{ $fbsPath }}" fill="none" stroke="#9333EA" stroke-width="1.8" />
            @endif

            @if (!empty($rbsPath))
                <path d="{{ $rbsPath }}" fill="none" stroke="#F97316" stroke-width="1.8" />
            @endif
        </svg>
    </div>
    @endif

    <table class="columns-table">
        <tr>
            <td class="col-readings">
                <div class="section-heading">READINGS</div>
                <table class="readings-grid">
                    <thead>
                        <tr>
                            <th class="center" style="width: 22px">#</th>
                            <th style="width: 95px">DATE</th>
                            <th style="width: 60px">TIME</th>
                            <th style="width: 45px">TYPE</th>
                            <th class="right" style="width: 55px">READING</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($readings as $reading)
                            <tr class="{{ $loop->iteration % 2 === 1 ? 'row-odd' : 'row-even' }}">
                                <td class="center">{{ $loop->iteration }}</td>
                                <td>{{ (new DateTime($reading->read_at))->format('D d-M-Y') }}</td>
                                <td>{{ (new DateTime($reading->read_at))->format('h:iA') }}</td>
                                <td>{{ strtoupper($reading->type) }}</td>
                                <td class="right">{{ number_format((float)$reading->reading, 1) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="center" style="padding: 14px; color: #6b7280; background: #ffffff;">
                                    No readings recorded for this period.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </td>

            <td class="col-summary">
                <div class="section-heading">WEEKLY SUMMARY</div>
                <table class="summary-grid">
                    <tr><td class="label-cell">MEAN</td><td class="val-cell">{{ number_format((float)($weeklyStats['mean'] ?? 0), 1) }}</td></tr>
                    <tr><td class="label-cell">MIN</td><td class="val-cell">{{ number_format((float)($weeklyStats['min'] ?? 0), 1) }}</td></tr>
                    <tr><td class="label-cell">MAX</td><td class="val-cell">{{ number_format((float)($weeklyStats['max'] ?? 0), 1) }}</td></tr>
                </table>

                <div class="section-heading">MONTHLY SUMMARY</div>
                <table class="summary-grid">
                    <tr><td class="label-cell">MEAN</td><td class="val-cell">{{ number_format((float)($monthlyStats['mean'] ?? 0), 1) }}</td></tr>
                    <tr><td class="label-cell">MIN</td><td class="val-cell">{{ number_format((float)($monthlyStats['min'] ?? 0), 1) }}</td></tr>
                    <tr><td class="label-cell">MAX</td><td class="val-cell">{{ number_format((float)($monthlyStats['max'] ?? 0), 1) }}</td></tr>
                </table>

                <div class="section-heading">QUARTERLY SUMMARY</div>
                <table class="summary-grid">
                    <tr><td class="label-cell">MEAN</td><td class="val-cell">{{ number_format((float)($quarterlyStats['mean'] ?? 0), 1) }}</td></tr>
                    <tr><td class="label-cell">MIN</td><td class="val-cell">{{ number_format((float)($quarterlyStats['min'] ?? 0), 1) }}</td></tr>
                    <tr><td class="label-cell">MAX</td><td class="val-cell">{{ number_format((float)($quarterlyStats['max'] ?? 0), 1) }}</td></tr>
                </table>

                <div class="section-heading">ALL TIME SUMMARY</div>
                <table class="summary-grid">
                    <tr><td class="label-cell">MEAN</td><td class="val-cell">{{ number_format((float)($allTimeStats['mean'] ?? 0), 1) }}</td></tr>
                    <tr><td class="label-cell">MIN</td><td class="val-cell">{{ number_format((float)($allTimeStats['min'] ?? 0), 1) }}</td></tr>
                    <tr><td class="label-cell">MAX</td><td class="val-cell">{{ number_format((float)($allTimeStats['max'] ?? 0), 1) }}</td></tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
