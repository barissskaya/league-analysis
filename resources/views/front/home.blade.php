@extends('front.layouts.master')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-8 offset-md-2 tournament-container">
                <div class="headline">
                    <span>Tournament Teams</span>
                </div>
                <ul id="teams"></ul>
                <button type="button" class="btn btn-success mt-4 js-generateFixtures">Generate Fixtures</button>
            </div>
        </div>
    </div>
@endsection
@section('custom_scripts')
    <script type="text/javascript">
        const teams = {
            selectors: {
                el: '.js-generateFixtures',
                listEl: '#teams',
            },
            loadTeams() {
                fetch('{{ route('api.teams.all') }}', {
                    method: "GET",
                    headers: {
                        "Content-Type": "text/plain; charset=UTF-8"
                    }
                }).then((response) => {
                    if (response.ok) {
                        return response.json();
                    }
                    throw new Error('Something went wrong');
                })
                    .then((teams) => {
                        let listEl = document.querySelector(this.selectors.listEl);
                        let htmlData = '';
                        teams.forEach((team, index) => {
                            htmlData += `<li>${team.name}</li>`;
                        });
                        listEl.innerHTML = htmlData;
                    })
                    .catch((error) => {
                        console.error(error)
                    });
            },
            handleClick() {
            },
            bindEvent() {
                const _this = this;
                let el = document.querySelector(this.selectors.el);
                el.addEventListener('click', () => {
                    _this.handleClick();
                });
            },
            init() {
                this.loadTeams();
                this.bindEvent();
            }
        }

        function onDocumentReady() {
            teams.init();
        }

        if (document.readyState !== 'loading') {
            onDocumentReady();
        } else {
            document.addEventListener('DOMContentLoaded', onDocumentReady);
        }
    </script>
@endsection
