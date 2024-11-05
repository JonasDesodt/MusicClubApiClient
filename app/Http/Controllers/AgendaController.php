<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use \DateTime;
use \DateInterval;
use Illuminate\Contracts\View\View;

class AgendaController extends Controller
{
    public function index(Request $request) : View
    {
        $validatedData = $request->validate([
            'page' => 'int|min:1',
            'search' => 'nullable|string|max:5',
            'from' => 'nullable|date',
            'until' => 'nullable|date|after_or_equal:from'
        ]);    

        $page = $validatedData['page'] ?? 1;
        $pageSize = 12;
        $from = $validatedData['from'] ?? null;
        $until = $validatedData['until'] ?? null;
        $search = $validatedData['search'] ?? null;
        
        if(!isset($from) && !isset($to))
        {
            $from = gmdate('Y-m-d H:i', strtotime(date('Y-m-d') . ' 0:0' . ' Europe/Brussels'));
        } 
        else 
        {
            if(isset($from))
            {
                $from = gmdate('Y-m-d H:i:s', strtotime($from . ' Europe/Brussels'));
            }

            if(isset($until))
            {
                $until = gmdate('Y-m-d H:i:s', strtotime($until . ' Europe/Brussels'));
            }
        }

        $queryParams = [
            'Page' => $page,
            'PageSize' => $pageSize,
            'From' => $from,
            'Until' => $until
        ];

        if(isset($search))
        {
            $queryParams['Search'] = $search;
        }

        $agendaResponse = Http::custom()
            ->withOptions(['verify' => false] /* dev only! */)
            ->get('https://localhost:7023/public/agenda', $queryParams);

        if(!$agendaResponse->successful())
        {
            return view('error', ['error' => 'data_fetch_failed', 'return_url' => url()->previous()]);
        }

        $agenda = json_decode($agendaResponse);

        return view('agenda.index', compact('agenda'));  
    }   

    public function detail(Request $request) : View
    {
        $locale = app()->getLocale();
        $id = $request->id;

        $lineupResponse = Http::custom()
            ->withOptions(['verify' => false] /* dev only! */)
            ->get('https://localhost:7023/public/agenda/'.$locale.'/'.$id);

        $lineup = json_decode($lineupResponse);        

        $lineup->start = null;

        if (count($lineup->acts) > 0) {
            foreach (array_reverse($lineup->acts) as $act) {
                if (!is_null($act->start)) {
                    $lineup->start = new DateTime($act->start);
                    break;
                }
            }
        }
        
        if (is_null($lineup->start)) {
            $lineup->start = (new DateTime($lineup->doors))->add(new DateInterval('PT30M'));
        }   

        return view('agenda.detail', compact('lineup'));
    }

    public function setLineupStart($lineup) {
        $lineup->start = null;

        if (count($lineup->acts) > 0) {
            foreach (array_reverse($lineup->acts) as $act) {
                if (!is_null($act->start)) {
                    $lineup->start = new DateTime($act->start);
                    break;
                }
            }
        }
        
        if (is_null($lineup->start)) {
            $lineup->start = (new DateTime($lineup->doors))->add(new DateInterval('PT30M'));
        }
    }
}