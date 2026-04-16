<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kesehatan</title>
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
        .badge {
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-vaksin {
            background-color: #22c55e;
            color: white;
        }
        .badge-sakit {
            background-color: #ef4444;
            color: white;
        }
        .badge-pengobatan {
            background-color: #f59e0b;
            color: white;
        }
    </style>
</head>
<body>
    <h1>Laporan Kesehatan</h1>
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
                <th class="text-center">Jenis</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->date->format('d/m/Y') }}</td>
                    <td>{{ $item->animal->farmer->name }}</td>
                    <td>{{ ucfirst($item->animal->type) }}</td>
                    <td class="text-center">
                        <span class="badge badge-{{ $item->type }}">
                            {{ ucfirst($item->type) }}
                        </span>
                    </td>
                    <td>{{ $item->description }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>