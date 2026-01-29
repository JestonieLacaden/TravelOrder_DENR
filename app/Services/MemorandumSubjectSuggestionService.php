<?php

namespace App\Services;

class MemorandumSubjectSuggestionService
{
    protected $keywordPatterns = [
        'budget' => 'Justification on Budget Utilization',
        'approval' => 'Request for Approval',
        'procurement' => 'Status Update on Procurement',
        'travel' => 'Travel Order Authorization',
        'leave' => 'Leave Application',
        'meeting' => 'Meeting Notice and Agenda',
        'training' => 'Training Program Notification',
        'report' => 'Submission of Report',
        'request' => 'Request for Assistance',
        'update' => 'Status Update',
        'implementation' => 'Implementation Guidelines',
        'compliance' => 'Compliance Reminder',
        'directive' => 'Directive on Policy Implementation',
        'reminder' => 'Reminder on Compliance',
        'information' => 'Information Dissemination',
        'evaluation' => 'Performance Evaluation Report',
        'inspection' => 'Inspection Schedule',
        'monitoring' => 'Monitoring and Evaluation Report',
    ];

    public function suggestSubjects($bodyText)
    {
        $suggestions = [];
        $bodyLower = strtolower($bodyText);

        // Count keyword occurrences
        $keywordCounts = [];
        foreach ($this->keywordPatterns as $keyword => $suggestion) {
            $count = substr_count($bodyLower, $keyword);
            if ($count > 0) {
                $keywordCounts[$keyword] = $count;
            }
        }

        // Sort by occurrence count
        arsort($keywordCounts);

        // Get top 3 suggestions
        $count = 0;
        foreach ($keywordCounts as $keyword => $occurrences) {
            if ($count >= 3) break;
            $suggestions[] = $this->keywordPatterns[$keyword];
            $count++;
        }

        // If no keywords found, provide default suggestions
        if (empty($suggestions)) {
            $suggestions = [
                'Memorandum',
                'Official Communication',
                'Information Dissemination',
            ];
        }

        return $suggestions;
    }

    public function generateSubjectFromBody($bodyText)
    {
        $suggestions = $this->suggestSubjects($bodyText);
        return $suggestions[0] ?? 'Memorandum';
    }
}
