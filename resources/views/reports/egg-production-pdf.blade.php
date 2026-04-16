<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Produksi Telur</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        h1 {
            text-align: center;
            margin-bottom: 5px;
        }
        .subtitle {
            text-align: center;
            margin-bottom: 20px;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <h1>Laporan Produksi Telur</h1>
    <div class="subtitle">
        @if($startDate || $endDate)
            Periode:
            {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d/m/Y') : '-' }}
            s/d
            {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d/m/Y') : '-' }}
        @else
            Semua Periode
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Tanggal</th>
                <th>Peternak</th>
                <th>Jenis Ternak</th>
                <th class="text-center">Jumlah Ekor</th>
                <th class="text-center">Jumlah Telur</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->date->format('d/m/Y') }}</td>
                    <td>{{ $item->animal->farmer->name }}</td>
                    <td>{{ ucfirst($item->animal->type) }}</td>
                    <td class="text-center">{{ $item->animal->quantity }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td>{{ $item->notes ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
        @if($data->count() > 0)
        <tfoot>
            <tr>
                <th colspan="5" class="text-right">Total Telur:</th>
                <th class="text-center">{{ $data->sum('quantity') }}</th>
                <th></th>
            </tr>
        </tfoot>
        @endif
    </table>
</body>
</html>