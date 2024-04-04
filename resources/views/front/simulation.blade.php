@extends('front.layouts.master')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Team Name</th>
                                <th class="text-center" scope="col">P</th>
                                <th class="text-center" scope="col">W</th>
                                <th class="text-center" scope="col">D</th>
                                <th class="text-center" scope="col">L</th>
                                <th class="text-center" scope="col">GF</th>
                                <th class="text-center" scope="col">GA</th>
                                <th class="text-center" scope="col">GD</th>
                                <th class="text-center" scope="col">Points</th>
                            </tr>
                        </thead>
                        <tbody id="league"></tbody>
                    </table>
                </div>
            </div>

            <div class="col-12 col-md-6"  id="weekMatches">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Week</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td></td>
                            <td class="text-end"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-12 col-md-6"  id="predictions">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Championship Predictions</th>
                            <th class="text-end" scope="col">%</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <button type="button" class="btn btn-success mt-4 js-playAllWeeks">Play All Weeks</button>
                <button type="button" class="btn btn-success mt-4 js-playNextWeek">Play Next Week</button>
                <button type="button" class="btn btn-danger mt-4 js-resetData">Reset Data</button>
            </div>
        </div>
    </div>
@endsection
@section('custom_scripts')
    <script type="text/javascript">
        const simulation = {
            selectors: {
                playNextEl: '.js-playNextWeek',
                playAllEl: '.js-playAllWeeks',
                resetEl: '.js-resetData',
                leagueEl: '#league',
                weekMatchesEl: '#weekMatches',
                predictionsEl: '#predictions',
            },
            loadLeague() {
                fetch('{{ route('api.league') }}', {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json; charset=UTF-8"
                    }
                }).then((response) => {
                    if (response.ok) {
                        return response.json();
                    }
                    throw new Error('Something went wrong');
                })
                    .then((league) => {
                        let listEl = document.querySelector(this.selectors.leagueEl);
                        let htmlData = '';
                        this.loadWeek();
                        league.league_details.forEach((detail, index) => {
                            htmlData += `
                                     <tr>
                                        <td>${detail.team.name}</td>
                                        <td class="text-center">${detail.played}</td>
                                        <td class="text-center">${detail.won}</td>
                                        <td class="text-center">${detail.drawn}</td>
                                        <td class="text-center">${detail.lost}</td>
                                        <td class="text-center">${detail.gf}</td>
                                        <td class="text-center">${detail.ga}</td>
                                        <td class="text-center">${detail.gd}</td>
                                        <td class="text-center">${detail.points}</td>
                                    </tr>
                                `;
                        });
                        listEl.innerHTML = htmlData;
                    })
                    .catch((error) => {
                        console.error(error)
                    });
            },
            loadPredictions(){
                fetch(`{{ route('api.predictions') }}`, {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json; charset=UTF-8"
                    }
                }).then((response) => {
                    if (response.ok) {
                        return response.json();
                    }
                    throw new Error('Something went wrong');
                })
                    .then((teams) => {
                        if(teams.length > 0){
                            let listEl = document.querySelector(this.selectors.predictionsEl);
                            let matchHtmlData = '';
                            teams.forEach((team, index) => {
                                matchHtmlData += `
                                        <tr>
                                            <td>${team.name}</td>
                                            <td class="text-end">${team.percent}</td>
                                        </tr>
                                    `;
                            });

                            let htmlData = `
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th scope="col">Championship Predictions</th>
                                            <th class="text-end" scope="col">%</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        ${matchHtmlData}
                                        </tbody>
                                    </table>
                                </div>
                            `;

                            listEl.innerHTML = htmlData;
                        }else{
                            let listEl = document.querySelector(this.selectors.predictionsEl);
                            listEl.innerHTML = '';
                        }
                    })
                    .catch((error) => {
                        console.error(error)
                    });
            },
            loadWeek(){

                fetch(`{{ route('api.week.show') }}`, {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json; charset=UTF-8"
                    }
                }).then((response) => {
                    if (response.ok) {
                        return response.json();
                    }
                    throw new Error('Something went wrong');
                })
                    .then((matches) => {
                        if(matches.length > 0){
                            let listEl = document.querySelector(this.selectors.weekMatchesEl);
                            let matchHtmlData = '';
                            matches.forEach((match, index) => {
                                matchHtmlData += `
                                        <tr>
                                            <td>${match.home_team.name}</td>
                                            <td class="text-end">${match.away_team.name}</td>
                                        </tr>
                                    `;
                            });

                            let htmlData = `
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th scope="col">Week ${matches[0].week}</th>
                                            <th scope="col"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        ${matchHtmlData}
                                        </tbody>
                                    </table>
                                </div>
                            `;

                            listEl.innerHTML = htmlData;
                        }else{
                            let listEl = document.querySelector(this.selectors.weekMatchesEl);
                            let htmlData = `
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th scope="col">All matches have been completed.</th>
                                            <th scope="col"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            `;

                            listEl.innerHTML = htmlData;
                        }
                    })
                    .catch((error) => {
                        console.error(error)
                    });
            },
            handleNextWeek() {
                fetch(`{{ route('api.week.play.next') }}`, {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json; charset=UTF-8"
                    }
                }).then((response) => {
                    if (response.ok) {
                        return response.json();
                    }
                    throw new Error('Something went wrong');
                })
                    .then((data) => {
                        this.loadLeague();
                        this.loadPredictions();
                    })
                    .catch((error) => {
                        console.error(error)
                    });
            },
            handleAllWeeks() {
                fetch(`{{ route('api.week.play.all') }}`, {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json; charset=UTF-8"
                    }
                }).then((response) => {
                    if (response.ok) {
                        return response.json();
                    }
                    throw new Error('Something went wrong');
                })
                    .then((data) => {
                        this.loadLeague();
                        this.loadPredictions();
                    })
                    .catch((error) => {
                        console.error(error)
                    });
            },
            handleResetData() {
                fetch(`{{ route('api.reset') }}`, {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json; charset=UTF-8"
                    }
                }).then((response) => {
                    if (response.ok) {
                        return response.json();
                    }
                    throw new Error('Something went wrong');
                })
                    .then((data) => {
                        window.location.href = '{{ route('home') }}';
                    })
                    .catch((error) => {
                        console.error(error)
                    });
            },
            bindEvent() {
                const _this = this;
                let playNextEl = document.querySelector(this.selectors.playNextEl);
                let resetEl = document.querySelector(this.selectors.resetEl);
                let playAllEl = document.querySelector(this.selectors.playAllEl);
                playNextEl.addEventListener('click', () => {
                    _this.handleNextWeek();
                });
                resetEl.addEventListener('click', () => {
                    _this.handleResetData();
                });

                playAllEl.addEventListener('click', () => {
                    _this.handleAllWeeks();
                });
            },
            init() {
                this.loadLeague();
                this.loadPredictions();
                this.bindEvent();
            }
        }

        function onDocumentReady() {
            simulation.init();
        }

        if (document.readyState !== 'loading') {
            onDocumentReady();
        } else {
            document.addEventListener('DOMContentLoaded', onDocumentReady);
        }
    </script>
@endsection
