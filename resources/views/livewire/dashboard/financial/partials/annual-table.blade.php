<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-4">
    <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-800">
            <i class="{{ $icon }} {{ $iconColor }} mr-2"></i> {{ $title }} — {{ $year }}
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide sticky left-0 bg-gray-50 z-10 min-w-[180px]">Descrição</th>
                    @foreach($months as $name)
                        <th class="px-2 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ $name }}</th>
                    @endforeach
                    <th class="px-3 py-3 text-right text-xs font-semibold text-teal-600 uppercase tracking-wide">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($rows as $row)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-3 py-2.5 sticky left-0 bg-white z-10">
                            <p class="font-medium text-gray-800">{{ $row['description'] }}</p>
                            <p class="text-xs text-gray-400">{{ $row['category'] }}</p>
                        </td>
                        @foreach($row['monthly'] as $value)
                            <td class="px-2 py-2.5 text-right text-gray-600 {{ $value > 0 ? '' : 'text-gray-300' }}">
                                {{ $value > 0 ? 'R$ ' . number_format($value, 2, ',', '.') : '—' }}
                            </td>
                        @endforeach
                        <td class="px-3 py-2.5 text-right font-semibold text-gray-800">R$ {{ number_format($row['total'], 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="14" class="px-4 py-10 text-center">
                            <i class="fas fa-inbox text-3xl text-gray-300 mb-3"></i>
                            <p class="text-sm text-gray-400">Nenhum lançamento para {{ $year }}</p>
                            <a href="{{ route('financial.transactions') }}" class="inline-block mt-3 text-sm text-teal-600 hover:text-teal-700 font-medium">
                                <i class="fas fa-magic mr-1"></i> Gerar lançamentos
                            </a>
                        </td>
                    </tr>
                @endforelse

                @if(count($rows) > 0)
                    <tr class="bg-gray-50">
                        <td class="px-3 py-3 font-semibold text-gray-700 sticky left-0 bg-gray-50 z-10">TOTAL {{ strtoupper($title) }}</td>
                        @foreach($columnTotals as $total)
                            <td class="px-2 py-3 text-right font-semibold text-gray-800">R$ {{ number_format($total, 2, ',', '.') }}</td>
                        @endforeach
                        <td class="px-3 py-3 text-right font-bold text-teal-600">R$ {{ number_format(array_sum($columnTotals), 2, ',', '.') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>