<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;

class OpenAIService
{
    private const DEFAULT_MODEL = 'gpt-4o-mini';

    /**
     * Generate a summary for the given text
     */
    public function generateSummary(string $text, string $type = 'standard', string $language = 'en'): array
    {
        $prompt = $this->buildPrompt($text, $type, $language);
        $model = config('docmind.openai.model', self::DEFAULT_MODEL);

        $response = $this->createChatCompletionWithModelFallback([
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
     * Translate an existing summary JSON into target language.
     * Keeps the same schema and returns JSON object.
     */
    public function translateSummary(array $summary, string $targetLanguage): array
    {
        $model = config('docmind.openai.model', self::DEFAULT_MODEL);

        $payload = json_encode([
            'title' => $summary['title'] ?? '',
            'overview' => $summary['overview'] ?? '',
            'key_points' => $summary['key_points'] ?? [],
            'action_items' => $summary['action_items'] ?? [],
            'keywords' => $summary['keywords'] ?? [],
            'important_facts' => $summary['important_facts'] ?? null,
            'obligations' => $summary['obligations'] ?? null,
            'risks' => $summary['risks'] ?? null,
            'findings' => $summary['findings'] ?? null,
        ], JSON_UNESCAPED_UNICODE);

        $targetLangName = $this->resolveLanguageName($targetLanguage);

        $prompt = <<<PROMPT
Translate the following JSON summary into {$targetLangName}.
Keep the same JSON keys and structure. Translate text values only.
Return JSON only.

JSON schema: {"title":"string","overview":"string","key_points":["string"],"action_items":["string"],"keywords":["string"],"important_facts":"string|null","obligations":"string|null","risks":"string|null","findings":"string|null"}

Input JSON:
{$payload}
PROMPT;

        $response = $this->createChatCompletionWithModelFallback([
            'model' => $model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a professional translator. Return valid JSON only.',
                ],
                [
                    'role' => 'user',
                    'content' => $prompt,
                ],
            ],
            'temperature' => 0.2,
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
        $langName = $this->resolveLanguageName($language);
        $lang = $language !== 'en' ? "You MUST respond entirely in {$langName}. All text values in the JSON must be written in {$langName}." : "";

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

    private function resolveLanguageName(string $code): string
    {
        return match ($code) {
            'en' => 'English',
            'es' => 'Spanish',
            'fr' => 'French',
            'de' => 'German',
            'it' => 'Italian',
            'pt' => 'Portuguese',
            'ru' => 'Russian',
            'tr' => 'Turkish',
            'ja' => 'Japanese',
            'zh-Hans' => 'Simplified Chinese',
            'zh-Hant' => 'Traditional Chinese',
            'nl' => 'Dutch',
            'id' => 'Indonesian',
            default => $code,
        };
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

    private function createChatCompletionWithModelFallback(array $payload): mixed
    {
        try {
            return OpenAI::chat()->create($payload);
        } catch (\Throwable $e) {
            $message = strtolower($e->getMessage());
            $isModelError = str_contains($message, 'model') &&
                (str_contains($message, 'does not exist') || str_contains($message, 'do not have access'));

            if (!$isModelError) {
                throw $e;
            }

            $fallbackModel = config('docmind.openai.fallback_model', self::DEFAULT_MODEL);
            $requestedModel = $payload['model'] ?? null;

            if (!$fallbackModel || $fallbackModel === $requestedModel) {
                throw $e;
            }

            $payload['model'] = $fallbackModel;
            \Log::warning('Primary OpenAI model failed, retrying with fallback model.', [
                'requested_model' => $requestedModel,
                'fallback_model' => $fallbackModel,
                'error' => $e->getMessage(),
            ]);

            return OpenAI::chat()->create($payload);
        }
    }
}

