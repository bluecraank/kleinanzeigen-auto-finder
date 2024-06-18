<div>
    @if($query->status == 'loading')
        <div class="container-full" style="height: 100vh">
            <div style="height: 100vh" class="p-5 text-center bg-body-tertiary rounded-3 align-items-center d-flex justify-content-center">
                <div>
                    <h1 class="text-body-emphasis">Suche läuft...</h1>
                    <p class="lead">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if($query->status == 'done')
        <div class="container-full" style="height: 100vh">
            <div style="height: 100vh" class="p-5 text-center bg-body-tertiary rounded-3 align-items-center d-flex justify-content-center">
                <div>
                    <h1 class="text-body-emphasis">Suche abgeschlossen</h1>
                    <p class="lead">
                        <div class="alert alert-success" role="alert">
                            <h4 class="alert-heading">Suche abgeschlossen!</h4>
                            <p>Es wurden {{ $query->results->count() }} Ergebnisse gefunden.</p>
                            <hr>
                            <p class="mb-0">Die Suche wurde in {{ $query->duration }} Sekunden abgeschlossen.</p>
                        </div>
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>
