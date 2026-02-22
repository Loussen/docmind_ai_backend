<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;

class OpenAIService
{
    /**
     * Generate a summary for the given text
     */
    public function generateSummary(string $text, string $type = 'standard', string $language = 'en'): array
    {
        $prompt = $this->buildPrompt($text, $type, $language);
        $model = config('docmind.openai.model', 'gpt-4o-mini');

        $response = OpenAI::chat()->create([
            'model' => $model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $this->getSystemPrompt($type),
                ],
                [
                    'role' => 'user',
                    'content' => $prompt,
                ],
            ],
            'temperature' => config('docmind.openai.temperature', 0.3),
            'max_tokens' => config('docmind.openai.max_tokens', 2048),
            'response_format' => ['type' => 'json_object'],
        ]);

        $content = $response->choices[0]->message->content;
        
        return json_decode($content, true) ?? [];
    }

    /**
     * Get the system prompt based on document type
     */
    private function getSystemPrompt(string $type): string
    {
        $base = "Expert document analyst. Return structured JSON summaries.";
        
        return match ($type) {
            'contract' => $base . " Focus on obligations, deadlines, risks.",
            'academic' => $base . " Focus on methodology, findings, conclusions.",
            'business' => $base . " Focus on metrics, action items, strategy.",
            default => $base,
        };
    }

    /**
     * Build the prompt for summary generation
     */
    private function buildPrompt(string $text, string $type, string $language): string
    {
        $lang = $language !== 'en' ? "Respond in {$language}." : "";

        $extra = match ($type) {
            'contract' => 'Include "obligations" and "risks" fields.',
            'academic' => 'Include "findings" field.',
            'business' => 'Include "important_facts" field with metrics.',
            default => "",
        };

        return <<<PROMPT
Summarize this document as JSON. {$lang}

JSON schema: {"title":"string max 100 chars","overview":"2-3 sentences","key_points":["3-7 items"],"action_items":["if any"],"keywords":["5-10"],"important_facts":"optional","obligations":"optional","risks":"optional","findings":"optional"}

{$extra}

Text:
{$text}
PROMPT;
    }

    /**
     * Check if OpenAI is properly configured
     */
    public function isConfigured(): bool
    {
        return !empty(config('openai.api_key'));
    }

    /**
     * Get available models
     */
    public function getAvailableModels(): array
    {
        try {
            $response = OpenAI::models()->list();
            return collect($response->data)
                ->pluck('id')
                ->filter(fn($id) => str_starts_with($id, 'gpt'))
                ->values()
                ->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }
}

