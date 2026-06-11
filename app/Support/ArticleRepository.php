<?php

namespace App\Support;

use Illuminate\Support\Collection;

class ArticleRepository
{
    /** @var Collection<int, array<string, mixed>>|null */
    private ?Collection $articles = null;

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function all(): Collection
    {
        return $this->load()
            ->sortByDesc('published_at')
            ->values();
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function recent(int $limit = 3): Collection
    {
        return $this->all()->take($limit);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findBySlug(string $slug): ?array
    {
        return $this->load()->firstWhere('slug', $slug);
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    private function load(): Collection
    {
        if ($this->articles === null) {
            $this->articles = collect(config('articles', []));
        }

        return $this->articles;
    }
}
