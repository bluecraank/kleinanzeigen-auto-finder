@extends('layouts.app')

@section('content')
    <div class="container-full" style="height: 100vh">
        <div style="height: 100vh"
            class="p-5 text-center bg-body-tertiary rounded-3 align-items-center d-flex justify-content-center">
            <div>
                <h1 class="text-body-emphasis">Speziell nach Autos auf kleinanzeigen.de suchen</h1>
                <p class="lead">
                <form action="{{ Route('search') }}" method="POST">
                    @csrf
                    <div class="input-group">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="searchquery" id="floatingInput"
                                placeholder="BMW E90 320d">
                            <label for="floatingInput">Welches Auto suchst du?</label>
                        </div>
                        <button class="btn btn-primary">Suchen</button>
                    </div>

                    <h5 class="mt-2">Erweiterte Suche</h5>
                    <div class="d-flex justify-content-center mt-2">
                        <div class="mb-3 text-start fw-bold" style="margin-right:20px;">
                            <label for="exampleFormControlInput1" class="form-label">Preis</label>
                            <div class="input-group input-group-sm mb-3">
                                <input type="number" class="form-control" placeholder="von" name="price_from">
                                <input type="number" class="form-control" placeholder="bis" name="price_to">
                            </div>
                        </div>

                        <div class="mb-3 text-start fw-bold">
                            <label for="exampleFormControlInput1" class="form-label">Kilometerstand</label>
                            <div class="input-group input-group-sm mb-3">
                                <select name="" id="" class="form-select">
                                    <option value="0">beliebig</option>
                                    <option value="50000">50.000km</option>
                                    <option value="100000">100.000km</option>
                                    <option value="150000">150.000km</option>
                                    <option value="200000">200.000km</option>
                                    <option value="250000">250.000km</option>
                                    <option value="300000">300.000km</option>
                                </select>
                                <select name="" id="" class="form-select">
                                    <option value="0">beliebig</option>
                                    <option value="50000">50.000km</option>
                                    <option value="100000">100.000km</option>
                                    <option value="150000">150.000km</option>
                                    <option value="200000">200.000km</option>
                                    <option value="250000">250.000km</option>
                                    <option value="300000">300.000km</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 text-start fw-bold" style="margin-left: 20px">
                            <label for="exampleFormControlInput1" class="form-label">Erstzulassung</label>
                            <div class="input-group input-group-sm mb-3">
                                <select name="" id="" class="form-select">
                                    <option value="0">beliebig</option>
                                    <option value="2010">2010</option>
                                    <option value="2011">2011</option>
                                    <option value="2012">2012</option>
                                    <option value="2013">2013</option>
                                    <option value="2014">2014</option>
                                    <option value="2015">2015</option>
                                    <option value="2016">2016</option>
                                    <option value="2017">2017</option>
                                    <option value="2018">2018</option>
                                    <option value="2019">2019</option>
                                    <option value="2020">2020</option>
                                    <option value="2021">2021</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                </select>
                                <select name="" id="" class="form-select">
                                    <option value="0">beliebig</option>
                                    <option value="2010">2010</option>
                                    <option value="2011">2011</option>
                                    <option value="2012">2012</option>
                                    <option value="2013">2013</option>
                                    <option value="2014">2014</option>
                                    <option value="2015">2015</option>
                                    <option value="2016">2016</option>
                                    <option value="2017">2017</option>
                                    <option value="2018">2018</option>
                                    <option value="2019">2019</option>
                                    <option value="2020">2020</option>
                                    <option value="2021">2021</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>

                </p>
                <span style="font-size: 0.8em" class="text-muted">Dieses Projekt steht in keiner Verbindung zu
                    kleinanzeigen.de</span>
                <br>
                <span style="font-size: 0.7em" class="mt-3 text-muted">© 2024 kleinanzeigen-auto-finder</span>
            </div>
        </div>
    </div>
@endsection
