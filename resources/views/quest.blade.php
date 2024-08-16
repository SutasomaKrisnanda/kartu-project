{{-- {{ dd($quests) }} --}}
<x-home.layout>
    {{-- This for the variables --}}
    <x-slot:nickname>{{ $user->nickname }}</x-slot>

    {{-- This is the main content --}}
    <h1 class="mt-4">Quests Menu</h1>
    <p>Welcome to Quests Menu</p>

    <div class="container">
        @foreach ($quests as $quest)
            <div class="{{ $quest->pivot->is_completed ? 'bg-info text-white' : 'bg-secondary' }} rounded p-3 mb-3 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="text-white mb-1">Quest 1: {{ $quest->description }} | Progress: {{ $quest->pivot->current_progress }}/{{ $quest->required_progress }}</h5>
                    <div class="progress mt-4" style="height: 10px;">
                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ $quest->pivot->current_progress / $quest->required_progress * 100 }}%" aria-valuenow="{{ $quest->pivot->current_progress }}" aria-valuemin="0" aria-valuemax="{{ $quest->required_progress }}"></div>
                    </div>
                </div>
                <div class="{{ $quest->pivot->is_completed ? '' : 'text-warning' }}">Reward: {{ $quest->reward }} XP</div>
            </div>
        @endforeach
    </div>

</x-home.layout>

