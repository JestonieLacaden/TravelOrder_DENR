<?php
// Temporary file to test the query
// Replace lines 159-169 in show.blade.php with:

$isReviewer = \App\Models\MemorandumWorkflowHistory::where('memorandum_id', $memorandum->id)
    ->where('to_user_id', Auth::id())
    ->where('action', 'forwarded')
    ->exists();

// Use fresh query to get latest reviewer action
$reviewerAction = \App\Models\MemorandumWorkflowHistory::where('memorandum_id', $memorandum->id)
    ->where('from_user_id', Auth::id())
    ->whereIn('action', ['received', 'approved', 'returned', 'revised'])
    ->latest('created_at')
    ->first();

$reviewerHasCompleted = $reviewerAction !== null;
$hasPendingAction = $isReviewer && !$reviewerHasCompleted && $memorandum->status === 'For Review';
