<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $response = Http::get('https://timesofindia.indiatimes.com/rssfeeds/-2128838597.cms?feedtype=json');
        $data = $response->json();

        // Paginate the news articles with 10 items per page
        $articles = collect($data['channel']['item']);
        $perPage = 5;
        $currentPage = $request->input('page') ?? 1; // Fetch the page parameter from the request
        $currentItems = $articles->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedData = new LengthAwarePaginator($currentItems, count($articles), $perPage, $currentPage);

        return view('news')->with('data', $paginatedData)->with('channel', $data['channel']);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $response = Http::get('https://timesofindia.indiatimes.com/rssfeeds/-2128838597.cms?feedtype=json');
        $data = $response->json();
    
        $articles = collect($data['channel']['item']);
        $filteredArticles = $articles->filter(function ($article) use ($query) {
            return strpos(strtolower($article['title']), strtolower($query)) !== false;
        });
    
        $perPage = 5;
        $currentPage = Paginator::resolveCurrentPage() ?? 1;
        $currentItems = $filteredArticles->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedData = new LengthAwarePaginator($currentItems, count($filteredArticles), $perPage, $currentPage);
    
        return view('news')->with('data', $paginatedData)->with('channel', $data['channel']);
    }
    public function sort(Request $request)
    {
        $column = $request->input('column', 'title'); // Default to sorting by title
        $direction = $request->input('direction', 'asc'); // Default to ascending order

        $response = Http::get('https://timesofindia.indiatimes.com/rssfeeds/-2128838597.cms?feedtype=json');
        $data = $response->json();

        $articles = collect($data['channel']['item']);
        $sortedArticles = $articles->sortBy($column, SORT_REGULAR, $direction === 'desc');

        $perPage = 10;
        $currentPage = Paginator::resolveCurrentPage() ?? 1;
        $currentItems = $sortedArticles->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedData = new LengthAwarePaginator($currentItems, count($sortedArticles), $perPage, $currentPage);

        return view('news')->with('data', $paginatedData)->with('channel', $data['channel']);
    }
}


