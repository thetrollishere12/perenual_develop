<div class="md:col-span-1 flex justify-between">
    <div class="p-1">
        <h3 class="text-lg font-medium text-gray-900">{{ $title }}</h3>

        <p class="mt-1 text-sm text-gray-600">
            {{ $description }}
        </p>
    </div>

    <div class="p-1">
        {{ $aside ?? '' }}
    </div>
</div>
