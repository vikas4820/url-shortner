@props([
    'columns' => [],
    'data' => [],
    'emptyMessage' => 'No data available.',
    'striped' => true,
    'hoverable' => true,
    'bordered' => true,
    'class' => '',
])

<?php
$tableClasses = 'w-full';
$stripedClass = $striped ? 'odd:bg-gray-50' : '';
$hoverClass = $hoverable ? 'hover:bg-gray-50' : '';
$borderClass = $bordered ? 'divide-y divide-gray-200' : '';
?>

<div class="overflow-x-auto rounded-xl border border-gray-200 {{ $class }}" {{ $attributes }}>
    <table class="{{ $tableClasses }} {{ $borderClass }}">
        <thead class="bg-gray-50">
            <tr>
                @foreach ($columns as $column)
                    <th
                        class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider {{ $column['class'] ?? '' }}"
                        {{ $column['width'] ? 'style="width: ' . $column['width'] . '"' : '' }}
                    >
                        {{ $column['label'] }}
                    </th>
                @endforeach
            </tr>
        </thead>

        <tbody class="bg-white {{ $stripedClass }} {{ $hoverClass }} divide-y divide-gray-200">
            @forelse ($data as $row)
                <tr class="{{ $row['_rowClass'] ?? '' }}">
                    @foreach ($columns as $column)
                        <td class="px-6 py-4 {{ $column['class'] ?? '' }}">
                            @if (isset($column['render']))
                                {{ $column['render']($row) }}
                            @elseif (isset($column['key']))
                                {{ $row[$column['key']] ?? '' }}
                            @else
                                {{ $row[$column['label']] ?? '' }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) }}" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center gap-3">
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="text-sm">{{ $emptyMessage }}</span>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>