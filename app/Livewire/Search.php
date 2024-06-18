<?php

namespace App\Livewire;

use App\Models\SearchQuery;
use App\Models\SearchResult;
use Livewire\Component;

class Search extends Component
{
    public $query;

    public function mount(SearchQuery $searchQuery)
    {
        $this->query = $searchQuery;
    }

    public function render()
    {
        return view('livewire.search');
    }
}
