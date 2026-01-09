<?php

namespace App\Services;

class SummaryFormatterService
{
    /**
     * Format and validate the summary data from OpenAI
     */
    public function format(array $data): array
    {
        return [
            'title' => $this->formatTitle($data['title'] ?? 'Untitled Document'),
            'overview' => $this->formatOverview($data['overview'] ?? ''),
            'key_points' => $this->formatKeyPoints($data['key_points'] ?? []),
            'action_items' => $this->formatActionItems($data['action_items'] ?? []),
            'keywords' => $this->formatKeywords($data['keywords'] ?? []),
            'important_facts' => $this->formatOptionalField($data['important_facts'] ?? null),
            'obligations' => $this->formatOptionalField($data['obligations'] ?? null),
            'risks' => $this->formatOptionalField($data['risks'] ?? null),
            'findings' => $this->formatOptionalField($data['findings'] ?? null),
        ];
    }

    /**
     * Format the title
     */
    private function formatTitle(string $title): string
    {
        // Limit title length
        $title = trim($title);
        
        if (strlen($title) > 200) {
            $title = substr($title, 0, 197) . '...';
        }
        
        // Remove quotes if wrapped
        $title = trim($title, '"\'');
        
        return $title ?: 'Untitled Document';
    }

    /**
     * Format the overview
     */
    private function formatOverview(string $overview): string
    {
        $overview = trim($overview);
        
        // Ensure it's not too long
        if (strlen($overview) > 2000) {
            $overview = substr($overview, 0, 1997) . '...';
        }
        
        return $overview ?: 'No overview available.';
    }

    /**
     * Format key points array
     */
    private function formatKeyPoints(array $points): array
    {
        if (empty($points)) {
            return ['No key points identified.'];
        }

        return collect($points)
            ->map(function ($point) {
                $point = trim((string) $point);
                // Remove bullet points or numbering at the start
                $point = preg_replace('/^[\d\.\-\*\•\◦\▪]+\s*/', '', $point);
                return $point;
            })
            ->filter(fn($point) => !empty($point))
            ->take(10) // Limit to 10 key points
            ->values()
            ->toArray();
    }

    /**
     * Format action items array
     */
    private function formatActionItems(array $items): array
    {
        if (empty($items)) {
            return [];
        }

        return collect($items)
            ->map(function ($item) {
                $item = trim((string) $item);
                // Remove bullet points or numbering at the start
                $item = preg_replace('/^[\d\.\-\*\•\◦\▪]+\s*/', '', $item);
                return $item;
            })
            ->filter(fn($item) => !empty($item))
            ->take(15) // Limit to 15 action items
            ->values()
            ->toArray();
    }

    /**
     * Format keywords array
     */
    private function formatKeywords(array $keywords): array
    {
        if (empty($keywords)) {
            return [];
        }

        return collect($keywords)
            ->map(function ($keyword) {
                $keyword = trim((string) $keyword);
                // Lowercase and remove special characters
                $keyword = preg_replace('/[^\w\s\-]/u', '', $keyword);
                return ucwords(strtolower($keyword));
            })
            ->filter(fn($keyword) => !empty($keyword) && strlen($keyword) > 1)
            ->unique()
            ->take(15) // Limit to 15 keywords
            ->values()
            ->toArray();
    }

    /**
     * Format optional text field
     */
    private function formatOptionalField(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        $value = trim($value);
        
        if (strlen($value) > 5000) {
            $value = substr($value, 0, 4997) . '...';
        }
        
        return $value ?: null;
    }

    /**
     * Calculate word count
     */
    public function calculateWordCount(string $text): int
    {
        return str_word_count(strip_tags($text));
    }

    /**
     * Extract sentences from text
     */
    public function extractSentences(string $text, int $count = 3): array
    {
        // Split by sentence endings
        $sentences = preg_split('/(?<=[.!?])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        
        return array_slice($sentences, 0, $count);
    }

    /**
     * Generate a slug from the title
     */
    public function generateSlug(string $title): string
    {
        return \Str::slug($title);
    }
}

