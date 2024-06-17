<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScrapeController extends Controller
{
    private $baseUrl = 'https://www.kleinanzeigen.de/s-seite:{{page}}/{{query}}/k0';
    private $removeSearch = true;

    public function search(Request $request)
    {
        $query = $request->input('query');
        $page = $request->input('currentPage') ?? 1;

        $anzeigen = $this->browseUrl($query, $page);

        return view('welcome', compact('anzeigen', 'query'));
    }

    public function browseUrl($query, $page = 1)
    {
        // dd($query, $page);
        $query = str_replace(' ', '-', $query);

        $url = str_replace('{{query}}', $query, $this->baseUrl);
        $url = str_replace('{{page}}', $page, $url);

        $web = new \Spekulatius\PHPScraper\PHPScraper;
        $web->go($url);

        // dd($url);

        $anzeigeNamen = $web->filter("//*[@class='ellipsis']");   // Name
        $preise = $web->filter("//*[@class='aditem-main--middle--price-shipping--price']"); // Preis
        $standort = $web->filter("//*[@class='aditem-main--top--left']"); // Standort
        $link =  $anzeigeNamen->extract(['href']); // Link
        $images = $web->filter("//*[@class='imagebox srpimagebox']"); // children img src = image
        $createDate = $web->filter("//*[@class='aditem-main--top--right']"); // Erstellungsdatum

        $anzeigen = [];

        foreach ($anzeigeNamen as $key => $anzeigeNamen) {
            $anzeigen[$key] = [
                'name' => trim(str_replace("  ", "", $anzeigeNamen->nodeValue)),
            ];

            if ((strpos($anzeigen[$key]['name'], 'Kaufe') !== false || strpos($anzeigen[$key]['name'], 'Ankauf') !== false || strpos($anzeigen[$key]['name'], 'Suche') !== false) && $this->removeSearch) {
                $anzeigen[$key]['unset'] = true;
            }

            $anzeigen[$key]['facelift'] = false;
            if (strpos($anzeigen[$key]['name'], 'LCI') !== false || strpos($anzeigen[$key]['name'], 'Facelift') !== false) {
                $anzeigen[$key]['facelift'] = true;
            }
        }

        foreach ($preise as $key => $preis) {
            $anzeigen[$key]['preis'] = trim(str_replace(["\"", "\r", "\n", "  "], "", $preis->nodeValue));
            $anzeigen[$key]['vb'] = false;
            if (strpos($anzeigen[$key]['preis'], 'VB') !== false) {
                $anzeigen[$key]['vb'] = true;
                $anzeigen[$key]['preis'] = str_replace('VB', '', $anzeigen[$key]['preis']);
            }

            $anzeigen[$key]['preis'] = preg_replace('/[^0-9]/', '', $anzeigen[$key]['preis']);
        }

        foreach ($standort as $key => $standort) {
            $anzeigen[$key]['standort'] = trim(str_replace(["\"", "\r", "\n", "  "], "", $standort->nodeValue));
            $anzeigen[$key]['standort'] = str_replace('&#8203', '', $anzeigen[$key]['standort']);
        }

        foreach ($createDate as $key => $createDate) {
            $anzeigen[$key]['createDate'] = trim(str_replace(["\"", "\r", "\n", "  "], "", $createDate->nodeValue));
        }

        foreach ($link as $key => $link) {
            $anzeigen[$key]['link'] = $link;
        }

        foreach ($images as $key => $image) {
            $anzeigen[$key]['image'] = $image->getElementsByTagName('img')[0]->getAttribute('src');
        }

        $anzeigen = array_filter($anzeigen, function($anzeige) {
            return !isset($anzeige['unset']);
        });

        return $anzeigen;
    }

    public function browseDetails($url) {
        $web = new \Spekulatius\PHPScraper\PHPScraper;

        $url = base64_decode($url);
        $web->go($url);

        $details = $web->filter("//*[@class='addetailslist--detail']");

        $return = [];

        $isCar = false;

        foreach ($details as $detail) {
            $detail = trim(str_replace(["\"", "\r", "\n", "  "], "", $detail->nodeValue));
            if (strpos($detail, 'Kilometerstand') !== false) {
                $isCar = true;
                $return['kilometerstand'] = str_replace('Kilometerstand', '', $detail);
            }

            if (strpos($detail, 'Erstzulassung') !== false) {
                $return['erstzulassung'] = str_replace('Erstzulassung', '', $detail);
            }

            if (strpos($detail, 'Leistung') !== false) {
                $return['leistung'] = str_replace('Leistung', '', $detail);
            }

            if (strpos($detail, 'Kraftstoffart') !== false) {
                $return['kraftstoffart'] = str_replace('Kraftstoffart', '', $detail);
            }

            if (strpos($detail, 'HU bis') !== false) {
                $return['hu'] = str_replace('HU bis', '', $detail);
            }

            if (strpos($detail, 'Getriebe') !== false) {
                $return['getriebe'] = str_replace('Getriebe', '', $detail);
            }

            if (strpos($detail, 'Fahrzeugtyp') !== false) {
                $return['typ'] = str_replace('Fahrzeugtyp', '', $detail);
            }
        }

        if (!$isCar) {
            $return = ['error' => true, 'message' => 'Not a car'];
        }

        return response()->json($return);
    }
}
