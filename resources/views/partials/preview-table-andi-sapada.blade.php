@if($previewError)
<div class="notification error">{{ $previewError }}</div>
@else
<table>
    <thead>
        <tr>
            <th>Bulan</th>
            <th>Jumlah Mahasiswa</th>
            <th>Pendapatan (Rp)</th>
        </tr>
    </thead>
    <tbody>
        @forelse($sheetData as $row)
        <tr>
            <td>{{ $row[0] ?? '-' }}</td>
            <td>{{ $row[1] ?? '-' }}</td>
            <td>
                {{-- PERBAIKAN: Hanya format jika ada nilainya --}}
                @if(!empty($row[2]))
                Rp {{ number_format((int)str_replace('.', '', $row[2]), 0, ',', '.') }}
                @else
                -
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="3" style="text-align: center;">Tidak ada data pratinjau.</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endif