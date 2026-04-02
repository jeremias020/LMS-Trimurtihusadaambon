<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penilaian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #4e73df;
            margin-bottom: 10px;
        }
        .header p {
            color: #666;
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fc;
            font-weight: bold;
            color: #5a5c69;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Penilaian</h1>
        <p>Periode: {{ date('d F Y') }}</p>
        <p>Export: {{ date('d F Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID</th>
                <th>Tipe</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Mata Pelajaran</th>
                <th>Judul</th>
                <th>Nilai</th>
                <th>Grade</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item['ID'] }}</td>
                    <td>{{ $item['Tipe'] }}</td>
                    <td>{{ $item['NIS'] }}</td>
                    <td>{{ $item['Nama Siswa'] }}</td>
                    <td>{{ $item['Kelas'] }}</td>
                    <td>{{ $item['Mata Pelajaran'] }}</td>
                    <td>{{ $item['Judul'] }}</td>
                    <td class="text-center">{{ $item['Nilai'] }}</td>
                    <td class="text-center">{{ $item['Grade'] }}</td>
                    <td>{{ $item['Status'] }}</td>
                    <td>{{ $item['Tanggal'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center">Tidak ada data penilaian</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dihasilkan secara otomatis dari LMS SMK Kesehatan Trimurti Husada</p>
    </div>
</body>
</html>
