<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeedbackRequest;
use App\Services\FeedbackService;

class FeedbackController extends Controller
{

    public function __construct(protected FeedbackService $feedbackService) {}

    public function index()
    {
        return $this->feedbackService->getAllFeedbacks();
    }

    public function store(StoreFeedbackRequest $request)
    {
        $validateData = $request->validated();

        $feedback = $this->feedbackService->createFeedback($validateData);

        return response()->json([
            'message' => 'Successfully created!',
            'feedback' => $feedback
        ], 201);
    }
}
