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
        $pageSize = $request->query('pageSize') ?? 1;

        $queryParams = [
            'Page' => $page,
            'PageSize' => $pageSize,
            'SortProperty' => 'Doors',
            'SortDirection' => 'Ascending'
        ];

        $search = $request->query('search'); 
        if(isset($search)){
            $queryParams['DeepSearch'] = $search; //TODO ==> add security
        }

        $lineupResponse = Http::custom()->withOptions(['verify' => false] /* dev only! */)->get('https://localhost:7023/Lineup', $queryParams);

        if(!$lineupResponse->successful())
        {
            return view('error', ['error' => 'data_fetch_failed', 'return_url' => url()->previous()]);
        }

        $lineups = json_decode($lineupResponse);

        if(!isset($lineups->data))
        {
            return view('error', ['error' => 'data_fetch_failed', 'return_url' => url()->previous()]);
        }

        $agenda = new stdClass; //$object = Object::first();
        $agenda->pagination = $lineups->paginationResponse;
        $agenda->lineups = $lineups->data;

        foreach($lineups->data as $lineup)
        {
            $actResponse = Http::custom()->withOptions(['verify' => false] /* dev only! */)->get('https://localhost:7023/Act', 
            [
                'Page' => 1,
                'PageSize' => 5,
                'LineupId' => $lineup->id,
                'SortProperty' => 'Start',
                'SortDirection' => 'Descending'
            ]);  
            
            if(!$actResponse->successful())
            {
                return view('error', ['error' => 'data_fetch_failed', 'return_url' => url()->previous()]);
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

    public function detail(Request $request)//, string $locale, int $id)
    {
        $id = $request->id;

        $lineupResponse = Http::custom()->withOptions(['verify' => false] /* dev only! */)->get('https://localhost:7023/Lineup/'.$id);  
            
        if(!$lineupResponse->successful())
        {
            return view('error', ['error' => 'data_fetch_failed', 'return_url' => url()->previous()]);
        }

        $lineup = json_decode($lineupResponse);

        if(!$lineup->data)
        {
            return view('error', ['error' => 'data_fetch_failed', 'return_url' => url()->previous()]);
        }

        $lineup->page = $request->query('page') ?? 1;

        $actResponseDesc = Http::custom()->withOptions(['verify' => false] /* dev only! */)->get('https://localhost:7023/Act', 
        [
            'Page' => 1,
            'PageSize' => 24,
            'LineupId' => $lineup->data->id,
            'SortProperty' => 'Start',
            'SortDirection' => 'Descending'
        ]);      

        if(!$actResponseDesc->successful())
        {
            return view('error', ['error' => 'data_fetch_failed', 'return_url' => url()->previous()]);
        }

        $actsDesc = json_decode($actResponseDesc); 

        if(!$actsDesc->data)
        {
            $actsDesc->data = [];
        }     


        $pages = round((double)$actsDesc->paginationResponse->totalCount / (double)$actsDesc->paginationResponse->pageSize, 0, PHP_ROUND_HALF_UP);

        for($i=2; $i < 2 + $pages; $i++) //needs more testing
        {
            $extraActResponseDesc = Http::custom()->withOptions(['verify' => false] /* dev only! */)->get('https://localhost:7023/Act', 
            [
                'Page' => $i,
                'PageSize' => 24,
                'LineupId' => $lineup->data->id,
                'SortProperty' => 'Start',
                'SortDirection' => 'Descending'
            ]);  
            
            if(!$extraActResponseDesc->successful())
            {
                return view('error', ['error' => 'data_fetch_failed', 'return_url' => url()->previous()]);
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