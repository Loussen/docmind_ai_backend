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

        $response = OpenAI::chat()->create([
            'model' => config('docmind.openai.model', 'gpt-4-turbo-preview'),
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
            'max_tokens' => config('docmind.openai.max_tokens', 4096),
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
        $basePrompt = "You are an expert document analyst. You provide clear, structured summaries in JSON format.";
        
        return match ($type) {
            'contract' => $basePrompt . " You specialize in legal contracts and agreements. Pay special attention to obligations, deadlines, and potential risks.",
            'academic' => $basePrompt . " You specialize in academic and research documents. Focus on methodology, findings, and conclusions.",
            'business' => $basePrompt . " You specialize in business documents. Focus on key metrics, action items, and strategic insights.",
            default => $basePrompt . " Provide comprehensive summaries for general documents.",
        };
    }

    /**
     * Build the prompt for summary generation
     */
    private function buildPrompt(string $text, string $type, string $language): string
    {
        $languageInstruction = $language !== 'en' 
            ? "Provide your response in {$language} language." 
            : "Provide your response in English.";

        $typeSpecificInstructions = match ($type) {
            'contract' => "
- List all contractual obligations for each party
- Identify key deadlines and dates
- Highlight any risks, penalties, or liabilities
- Note any termination clauses or conditions",
            'academic' => "
- Summarize the research methodology
- List key findings and conclusions
- Note any limitations mentioned
- Highlight statistical significance if applicable",
            'business' => "
- Extract key business metrics and KPIs
- Identify strategic recommendations
- List action items with owners if mentioned
- Note any financial figures or projections",
            default => "",
        };

        return <<<PROMPT
Analyze the following document and provide a structured summary in JSON format.

{$languageInstruction}

Your response must be a valid JSON object with the following structure:
{
    "title": "A concise title for the document (max 100 characters)",
    "overview": "A comprehensive 2-3 sentence overview of the document",
    "key_points": ["Array of 3-7 key points from the document"],
    "action_items": ["Array of action items or next steps, if any"],
    "keywords": ["Array of 5-10 relevant keywords"],
    "important_facts": "String with important facts, numbers, or dates (optional)",
    "obligations": "String with obligations if this is a contract (optional)",
    "risks": "String with risks or concerns if applicable (optional)",
    "findings": "String with research findings if this is academic (optional)"
}

Instructions:
- Be concise but comprehensive
- Extract the most important information
- Use clear, professional language
- Ensure all arrays have at least one item
{$typeSpecificInstructions}

Document Text:
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

