@props([
    'rows' => [],
    'caption' => null,
])

<div class="overflow-x-auto border border-[#5D6E6E]/25" data-animate-table>
    <table class="data-table">
        @if ($caption)
            <caption class="text-left p-4 font-display text-[0.75rem] tracking-[0.24em] text-[#FFFFFF] border-b border-[#5D6E6E]/25 bg-[#0A0A0A]">{{ $caption }}</caption>
        @endif
        <thead>
            <tr>
                <th scope="col">Campo</th>
                <th scope="col">Valor</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr data-animate="table-row">
                    <th scope="row" class="font-classified text-[0.7rem] tracking-[0.22em] text-[#9CACAD] py-3 px-4 text-left font-normal align-top">
                        {{ $row['label'] }}
                    </th>
                    <td class="mono py-3 px-4">{{ $row['value'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
