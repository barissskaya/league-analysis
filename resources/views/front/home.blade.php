@extends('front.layouts.master')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-8 offset-md-2">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Tournament Teams</th>
                        </tr>
                        </thead>
                        <tbody id="teams">
                        </tbody>
                    </table>
                </div>
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
                        "Content-Type": "application/json; charset=UTF-8"
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
                            htmlData += `<tr><td>${team.name}</td></tr>`;
                        });
                        listEl.innerHTML = htmlData;
                    })
                    .catch((error) => {
                        console.error(error)
                    });
            },
            handleClick() {
                fetch('{{ route('api.fixture.generate') }}', {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json; charset=UTF-8",
                        'X-CSRF-TOKEN': "{{ @csrf_token() }}"
                    }
                }).then((response) => {
                    if (response.ok) {
                        return response.json();
                    }
                    throw new Error('Something went wrong');
                })
                    .then((data) => {
                        window.location.href = '{{ route('fixtures') }}';
                    })
                    .catch((error) => {
                        console.error(error)
                    });
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
