{{-- {{ dd(json_decode($playerStatus->cooldown)[0]) }} --}}
<!DOCTYPE html>
<html lang="en">

<body>
    <div class='container-game'>
        <div class='play-area-game'>
            <div class="left-menu-container">
                <div class='left-menu-game'>
                    <div class="left-box">
                        <div class='menu-settings-game'><i class='fa-solid fa-bars'></i></div>
                        <div class='menu-history-game'><i class='fa-solid fa-clock-rotate-left'></i></div>
                    </div>
                    <div class="history-box">
                        <div class="history-title">
                            <span>Player</span>
                            <span>Opponent</span>
                        </div>
                        <div class="history-card-container">
                            <div class="player-history">
                                @foreach ($historya as $move)
                                    @if ($move->move)
                                        <div class="history-card-game {{ $move->win ? 'active' : '' }}"
                                            data-card-token="{{ $move->move->token }}">
                                            <img src="{{ $move->move->image }}" alt="{{ $move->move->name }}"
                                                title="{{ $move->move->name }}">
                                        </div>
                                    @else
                                        <div class="history-card-game skip" data-card-token="test">
                                            <img src="/images/card/slot-skipped.webp" alt="test" title="test">
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="opponent-history">
                                @foreach ($historyb as $move)
                                    @if ($move->move)
                                        <div class="history-card-game {{ $move->win ? 'active' : '' }}"
                                            data-card-token="{{ $move->move->token }}">
                                            <img src="{{ $move->move->image }}" alt="{{ $move->move->name }}"
                                                title="{{ $move->move->name }}">
                                        </div>
                                    @else
                                        <div class="history-card-game skip" data-card-token="test">
                                            <img src="/images/card/slot-skipped.webp" alt="test" title="test">
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class='left-view-game'>
                <div class='bar-player-game'>
                    <div class='hp-left-game' style="width: {{ $playerStatus->hp * 12.5 }}%;"></div>
                    <img src='/images/game/bar-hp.png' alt='Bar'>
                </div>
                <div class='player-name-game'>{{ $player->nickname }}</div>
                <div class='player-effect-game'>Some Effect</div>
            </div>
            <div class='center-view-game'>
                <div class='opponent-play-game'>
                    <div class='dummy-played-game'>
                        <div class='pl-text'>Played</div>
                    </div>
                </div>
                <div class='player-play-game'>
                    <div class='dummy-played-game'>
                        <div class='pl-text'>Played</div>
                    </div>
                </div>
            </div>
            <div class='right-view-game'>
                <div class='bar-opponent-game'>
                    <div class='hp-left-game' style="width: {{ $opponentStatus->hp * 12.5 }}%;"></div>
                    <img src='/images/game/bar-hp.png' alt='Bar'>
                </div>
                <div class='opponent-name-game'>{{ $opponent->nickname }}</div>
                <div class='opponent-effect-game'>Some Effect</div>
            </div>
            <div class='right-menu-game'>
                <div class='dummy-cooldown-game'>
                    @if ($opponentStatus->cooldown)
                        <img src="{{ json_decode($opponentStatus->cooldown)[0]->image }}"
                            alt="{{ json_decode($opponentStatus->cooldown)[0]->name }}"
                            title="{{ json_decode($opponentStatus->cooldown)[0]->name }}">
                        <div class="cooldown-count">{{ json_decode($opponentStatus->cooldown)[0]->duration }}</div>
                    @endif
                </div>
            </div>
        </div>
        <div class='card-area-game'>
            <div class='card-container-game'>
                <div class='cooldown-player-game'>
                    <div class='dummy-cooldown-game'>
                        @if ($playerStatus->cooldown)
                            <img src="{{ json_decode($playerStatus->cooldown)[0]->image }}"
                                alt="{{ json_decode($playerStatus->cooldown)[0]->name }}"
                                title="{{ json_decode($playerStatus->cooldown)[0]->name }}">
                            <div class="cooldown-count">{{ json_decode($playerStatus->cooldown)[0]->duration }}</div>
                        @endif
                    </div>
                </div>
                <div class='card-ready-game'>
                    @foreach ($cards as $card)
                        <div class='dummy-card-game
                        {{ json_decode($playerStatus->cooldown) && in_array($card->token, array_column(json_decode($playerStatus->cooldown), 'token')) ? 'cooldown' : '' }}'
                            data-card-token="{{ $card->token }}">
                            <img src="{{ $card->image }}" alt="{{ $card->name }}" title="{{ $card->name }}">
                            @if (json_decode($playerStatus->cooldown) &&
                                    in_array($card->token, array_column(json_decode($playerStatus->cooldown), 'token')))
                                <div class="cooldown-count">
                                    @foreach (json_decode($playerStatus->cooldown) as $cooldown)
                                        @if ($cooldown->token == $card->token)
                                            {{ $cooldown->duration }}
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach

                </div>
                <div class='right-card-container-game'></div>
            </div>
        </div>
    </div>

    <div class="menu-box">
        <div class="menu-content">
            <section class="timer-game"> 00 : 00</section>
            <section class="menu-section-1">Music : ON</section>
            <section class="menu-section-2">Volume : 50%</section>
            <section class="menu-section-3"><a href="/home">Exit</a></section>
        </div>
    </div>

    <div class="skip-button">
        <button class="skip-button-game">Skip</button>
    </div>

    <div class="tutorial-popup">
        Confirm your card by click the check button
    </div>
</body>

</html>
