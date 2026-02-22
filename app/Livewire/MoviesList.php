<?php

namespace App\Livewire;

use App\Models\Movie;
use Livewire\Component;
use Livewire\WithPagination;

class MoviesList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'release_date';
    public $sortDirection = 'desc';
    public $perPage = 12;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'release_date'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setSortBy($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'desc';
        }

        $this->resetPage();
    }

    public function render()
    {
        $movies = Movie::query()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', "%{$this->search}%")
                    ->orWhere('overview', 'like', "%{$this->search}%");
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.movies-list', [
            'movies' => $movies,
        ]);
    }
}
