@extends('front.layouts.master')
@section('content')
    <div class="container">
        <div class="row" id="weeks">

        </div>
        <div class="row">
            <div class="col-12">
                <button type="button" class="btn btn-success mt-4 js-startSimulation">Start Simulation</button>
            </div>
        </div>
    </div>
@endsection
@section('custom_scripts')
    <script type="text/javascript">
        const fixtures = {
            selectors: {
                el: '.js-startSimulation',
                listEl: '#weeks',
            },
            loadFixture() {
                fetch('{{ route('api.fixture.all') }}', {
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
                    .then((fixtures) => {
                        let listEl = document.querySelector(this.selectors.listEl);
                        let htmlData = '';
                        for(let key in fixtures){
                            let matchesHtmlData = [];
                            fixtures[key].matches.forEach((match, index) => {
                                matchesHtmlData += `
                                    <tr>
                                        <td>${match.homeTeam}</td>
                                        <td class="text-end">${match.awayTeam}</td>
                                    </tr>
                                `;
                            });

                            htmlData += `
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th scope="col">Week ${fixtures[key].week}</th>
                                            <th scope="col"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        ${matchesHtmlData}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            `;
                        }
                        listEl.innerHTML = htmlData;
                    })
                    .catch((error) => {
                        console.error(error)
                    });
            },
            handleClick() {
                window.location.href = '{{ route('simulation') }}';
            },
            bindEvent() {
                const _this = this;
                let el = document.querySelector(this.selectors.el);
                el.addEventListener('click', () => {
                    _this.handleClick();
                });
            },
            init() {
                this.loadFixture();
                this.bindEvent();
            }
        }

        function onDocumentReady() {
            fixtures.init();
        }

        if (document.readyState !== 'loading') {
            onDocumentReady();
        } else {
            document.addEventListener('DOMContentLoaded', onDocumentReady);
        }
    </script>
@endsection
