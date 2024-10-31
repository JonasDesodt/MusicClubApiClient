<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use stdClass;
use \DateTime;
use \DateInterval;

class AgendaController extends Controller
{
    public function index(Request $request)
    {       
        $page = $request->query('page') ?? 1;
        $pageSize = $request->query('pageSize') ?? 12;

        $lineupResponse = Http::withOptions(['verify' => false] /* dev only! */)->get('https://localhost:7023/Lineup', 
        [
            'Page' => $page,
            'PageSize' => $pageSize,
            'SortProperty' => 'Doors',
            'SortDirection' => 'Ascending'
        ]);

        if(!$lineupResponse->successful())
        {
            return view('agenda.index', ['error' => 'Failed to fetch data']);
        }

        $lineups = json_decode($lineupResponse);

        if(!$lineups->data)
        {
            return view('agenda.index', ['error' => 'Failed to fetch data']);
        }

        $agenda = new stdClass; //$object = Object::first();
        $agenda->pagination = $lineups->paginationResponse;
        $agenda->lineups = $lineups->data;

        foreach($lineups->data as $lineup)
        {
            $actResponse = Http::withOptions(['verify' => false] /* dev only! */)->get('https://localhost:7023/Act', 
            [
                'Page' => 1,
                'PageSize' => 5,
                'LineupId' => $lineup->id,
                'SortProperty' => 'Start',
                'SortDirection' => 'Descending'
            ]);  
            
            if(!$actResponse->successful())
            {
                return view('agenda.index', ['error' => 'Failed to fetch data']);
            }

            $acts = json_decode($actResponse);     

            if(!$acts->data)
            {
                $acts->data = [];
            }

            $lineup->acts = $acts->data;                                       
        }        

        return view('agenda.index', compact('agenda'));  
    }

    public function detail(Request $request, int $id)
    {
        $lineupResponse = Http::withOptions(['verify' => false] /* dev only! */)->get('https://localhost:7023/Lineup/'.$id);  
            
        if(!$lineupResponse->successful())
        {
            return view('agenda.detail', ['error' => 'Failed to fetch data']);
        }

        $lineup = json_decode($lineupResponse);

        if(!$lineup->data)
        {
            return view('agenda.detail', ['error' => 'Failed to fetch data']);
        }

        $lineup->page = $request->query('page') ?? 1;

        $actResponseDesc = Http::withOptions(['verify' => false] /* dev only! */)->get('https://localhost:7023/Act', 
        [
            'Page' => 1,
            'PageSize' => 24,
            'LineupId' => $lineup->data->id,
            'SortProperty' => 'Start',
            'SortDirection' => 'Descending'
        ]);      

        if(!$actResponseDesc->successful())
        {
            return view('agenda.index', ['error' => 'Failed to fetch data']);
        }

        $actsDesc = json_decode($actResponseDesc); 

        if(!$actsDesc->data)
        {
            $actsDesc->data = [];
        }     


        $pages = round((double)$actsDesc->paginationResponse->totalCount / (double)$actsDesc->paginationResponse->pageSize, 0, PHP_ROUND_HALF_UP);

        for($i=2; $i < 2 + $pages; $i++) //needs more testing
        {
            $extraActResponseDesc = Http::withOptions(['verify' => false] /* dev only! */)->get('https://localhost:7023/Act', 
            [
                'Page' => $i,
                'PageSize' => 24,
                'LineupId' => $lineup->data->id,
                'SortProperty' => 'Start',
                'SortDirection' => 'Descending'
            ]);  
            
            if(!$extraActResponseDesc->successful())
            {
                return view('agenda.index', ['error' => 'Failed to fetch data']);
            } 
            
            $extraActsDesc = json_decode($extraActResponseDesc); 

            if(!$extraActsDesc->data)
            {
                $extraActsDesc->data = [];
            }    

            $actsDesc->data = array_merge($actsDesc->data, $extraActsDesc->data);
        }
        
        $lineup->data->acts = $actsDesc; // if extra acts are added => paginationResponse not up to date !

        if(count($lineup->data->acts->data) > 0)
        {
            $lineup->data->start = new DateTime(end($lineup->data->acts->data)->start);
        }      
        else 
        {
            $lineup->data->start = (new DateTime($lineup->data->doors))->add(new DateInterval('PT' . 30 . 'M'));
        }

        return view('agenda.detail', compact('lineup'));  
    }
}