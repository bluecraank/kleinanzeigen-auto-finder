@extends('layouts.app')

@section('content')
    <div class="container-full">
        <div class="p-5 text-center bg-body-tertiary rounded-3">
            <h1 class="text-body-emphasis">kleinanzeigen-auto-finder.de</h1>
            <p class="lead">
            <form action="{{ Route('search') }}" method="GET">
                @csrf
                <div class="input-group">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="query" id="floatingInput"
                            value="{{ $query ?? '' }}" placeholder="BMW E90 320d">
                        <label for="floatingInput">Welches Auto suchst du?</label>
                    </div>
                    <button class="btn btn-primary">Suchen</button>
                </div>
            </form>

            @if (!empty($query))
                <div class="statistics text-muted float-start mt-3">
                    <span class="offers"></span> Anzeigen auf <span class="pages"></span> Seiten gefunden
                </div>

                <div class="float-end mt-3">
                    <div class="spinner-border spinner-border-sm" role="status">
                    </div>

                    Seite <span class="currentPage"></span> von <span class="maxPage"></span> geladen <button
                        id="loadNextPages" disabled class="ml-1 btn btn-sm btn-secondary">Weitere 5 Seiten laden</button>
                </div>
            @endif
            </p>
        </div>
    </div>

    <div class="container-full">
        <div class="table-responsive">
            <table class="table table-striped align-middle" data-toggle="table" data-search="true">
                <thead>
                    <tr>
                        <th></th>
                        <th>Titel</th>
                        <th data-sortable="true" data-field="price">Preis</th>
                        <th data-sortable="true" data-field="kilometerstand">Kilometerstand</th>
                        <th data-sortable="true" data-field="leistung">Leistung</th>
                        <th data-sortable="true" data-field="kraftstoffart">Kraftstoffart</th>
                        <th data-sortable="true" data-field="erstzulassung">Erstzulassung</th>
                        <th data-sortable="true" data-field="hu">HU</th>
                        <th data-sortable="true" data-field="facelift">Facelift</th>
                        <th data-sortable="true" data-field="getriebe">Getriebe</th>
                        <th data-sortable="true" data-field="typ">Fahrzeugtyp</th>
                        <th>Inseriert</th>
                        <th>Standort</th>
                        <th>Link</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($anzeigen as $result)
                        <tr data-url="https://kleinanzeigen.de{{ $result['link'] }}" data-scrape-async="true">
                            <td><img src="{{ $result['image'] }}" width="150px" style="object-fit: contain  "
                                    height="100px" class="rounded" alt="">
                            <td>{{ $result['name'] }}</td>
                            <td>{{ $result['preis'] }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{ $result['facelift'] ? 'Ja' : 'Nein' }}</td>
                            <td></td>
                            <td></td>
                            <td>{{ $result['createDate'] }}</td>
                            <td>{{ $result['standort'] ?? 'k.A' }}</td>
                            <td><a target="_blank" href="https://kleinanzeigen.de{{ $result['link'] }}">Link</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <script>
            let currentPage = 1;
            let maxPage = 1;
            var scrapeUntilLastPage = true;

            function scraper(url, element, _callback) {
                console.log('Scrape: ' + url);
                let base64Url = btoa(url);

                fetch('/scrape/details/' + base64Url, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(text => {

                        if (text.error) {
                            // Remove this table row if an error occurred
                            element.remove();
                            return;
                        }

                        console.log(text);

                        let kilometerstand = text.kilometerstand ?? 'k.A';
                        let leistung = text.leistung ?? 'k.A';
                        let erstzulassung = text.erstzulassung ?? 'k.A';
                        let hu = text.hu ?? 'k.A';
                        let kraftstoffart = text.kraftstoffart ?? 'k.A';
                        let createdate = text.createDate ?? 'k.A';
                        let facelift = text.facelift ?? 'k.A';
                        let getriebe = text.getriebe ?? 'k.A';
                        let typ = text.typ ?? 'k.A';


                        element.querySelector('td:nth-child(4)').textContent = kilometerstand;
                        element.querySelector('td:nth-child(5)').textContent = leistung;
                        element.querySelector('td:nth-child(6)').textContent = kraftstoffart;
                        element.querySelector('td:nth-child(7)').textContent = erstzulassung;
                        element.querySelector('td:nth-child(8)').textContent = hu;
                        // element.querySelector('td:nth-child(8)').textContent = facelift;
                        element.querySelector('td:nth-child(10)').textContent = getriebe;
                        element.querySelector('td:nth-child(11)').textContent = typ;
                        // element.querySelector('td:nth-child(10)').textContent = createdate;

                        element.setAttribute('data-scraped', 'true');

                        // update counter
                        document.querySelector('.statistics .offers').textContent = document.querySelectorAll(
                            '[data-scraped="true"]').length;
                        document.querySelector('.statistics .pages').textContent = currentPage;

                        // update page statistic
                        document.querySelector('.currentPage').textContent = currentPage;
                        document.querySelector('.maxPage').textContent = maxPage;

                        if (_callback) {
                            _callback();
                        }
                    });
            }

            // Load next page if above script is done
            function scrapeNextPage() {
                console.log('Scrape next page');

                currentPage++;

                fetch('/scrape/next?currentPage=' + currentPage + '&query={{ $query }}', {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(rows => {

                        console.log(rows);

                        rows.forEach(row => {

                            if (document.querySelector(`[data-url="https://kleinanzeigen.de${row.link}"]`)) {
                                return;
                            }

                            let tr = document.createElement('tr');
                            tr.setAttribute('data-url', 'https://kleinanzeigen.de' + row.link);
                            tr.setAttribute('data-scrape-async', 'true');

                            tr.innerHTML = `
                                <td><img src="${row.image}" width="150px" style="object-fit: contain  " height="100px" class="rounded" alt=""></td>
                                <td>${row.name}</td>
                                <td>${row.preis}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>${row.facelift ? 'Ja' : 'Nein'}</td>
                                <td></td>
                                <td></td>
                                <td>${row.createDate}</td>
                                <td>${row.standort ?? 'k.A'}</td>
                                <td><a target="_blank" href="https://kleinanzeigen.de${row.link}">Link</a></td>
                            `;

                            document.querySelector('tbody').appendChild(tr);

                            // Scrape details
                            let url = tr.getAttribute('data-url');
                            scraper(url, tr);

                            // scrape next site if all scraped
                            if (document.querySelectorAll('[data-scrape-async="true"]').length === document
                                .querySelectorAll(
                                    '[data-scraped="true"]').length) {
                                scrapeNextPage();
                            }
                        });
                    });
            }

            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('[data-scrape-async="true"]').forEach((element) => {
                    let url = element.getAttribute('data-url');
                    scraper(url, element, () => {

                    });
                });
            });

            // document.querySelectorAll('[data-scrape-async="true"]').forEach((element) => {
            //     let url = element.getAttribute('data-url');
            //     scraper(url, element, () => {

            //     });
            // });

            setInterval(() => {
                if (currentPage >= maxPage) {
                    // Hide loader
                    document.querySelector('.spinner-border').style.display = 'none';
                    document.getElementById('loadNextPages').disabled = false;
                    return;
                }

                if (scrapeUntilLastPage) {
                    if (document.querySelectorAll('[data-scrape-async="true"]').length === document
                        .querySelectorAll(
                            '[data-scraped="true"]').length) {
                        scrapeNextPage();
                    }
                }
            }, 10000);

            document.getElementById('loadNextPages').addEventListener('click', () => {
                maxPage += 5;
                document.getElementById('loadNextPages').disabled = true;
                document.querySelector('.spinner-border').style.display = 'inline-block';
            });
        </script>
    </div>
@endsection
