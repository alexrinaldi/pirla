<x-filament-panels::page>
    <div class="overflow-x-auto">
        <div class="min-w-full border rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="sticky left-0 z-10 bg-gray-50 dark:bg-gray-800 px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider border-r">
                            Room
                        </th>
                        @foreach($this->getDateRange() as $date)
                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider min-w-[100px]">
                                <div>{{ $date->format('D') }}</div>
                                <div class="font-bold">{{ $date->format('d') }}</div>
                                <div>{{ $date->format('M') }}</div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($this->getRooms() as $room)
                        <tr>
                            <td class="sticky left-0 z-10 bg-white dark:bg-gray-900 px-4 py-3 whitespace-nowrap border-r font-medium">
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $room->room_number }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $room->roomType->name }}</div>
                            </td>
                            @foreach($this->getDateRange() as $date)
                                @php
                                    $reservation = $this->getReservationForRoomAndDate($room->id, $date);
                                @endphp
                                <td class="px-2 py-3 text-center text-xs {{ $reservation ? 'bg-blue-100 dark:bg-blue-900' : 'bg-gray-50 dark:bg-gray-800' }}">
                                    @if($reservation)
                                        <div class="text-blue-900 dark:text-blue-100 font-semibold">
                                            {{ $reservation->code }}
                                        </div>
                                        <div class="text-blue-700 dark:text-blue-300">
                                            {{ $reservation->guests->first()?->first_name }}
                                        </div>
                                    @else
                                        <div class="text-gray-400">-</div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
        <p><strong>Legend:</strong></p>
        <ul class="list-disc list-inside mt-2">
            <li><span class="inline-block w-4 h-4 bg-blue-100 dark:bg-blue-900 border mr-2"></span> Reserved</li>
            <li><span class="inline-block w-4 h-4 bg-gray-50 dark:bg-gray-800 border mr-2"></span> Available</li>
        </ul>
    </div>
</x-filament-panels::page>
