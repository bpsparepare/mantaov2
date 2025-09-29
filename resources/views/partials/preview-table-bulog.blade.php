@if($previewError)
<div class="notification error">{{ $previewError }}</div>
@else
<table>
    <thead>
        <tr>
            <th>Komoditas</th>
            <th>Satuan</th>
            <th>Jan</th>
            <th>Feb</th>
            <th>Mar</th>
            <th>Apr</th>
            <th>Mei</th>
            <th>Jun</th>
            <th>Jul</th>
            <th>Agu</th>
            <th>Sep</th>
            <th>Okt</th>
            <th>Nov</th>
            <th>Des</th>
        </tr>
    </thead>
    <tbody>
        @forelse($sheetData as $row)
        <tr>
            {{-- Loop melalui setiap sel dalam baris, sekarang ada 14 kolom --}}
            @for ($i = 0; $i < 14; $i++)
                <td>{{ $row[$i] ?? '-' }}</td>
                @endfor
        </tr>
        @empty
        <tr>
            <td colspan="14" style="text-align: center;">Tidak ada data pratinjau yang dapat ditampilkan.</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endif