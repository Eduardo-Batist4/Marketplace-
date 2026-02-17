<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeedbackRequest;
use App\Http\Requests\UpdateFeedbackRequest;
use App\Http\Resources\FeedbackResource;
use App\Services\FeedbackService;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class FeedbackController extends Controller
{
    public function __construct(protected FeedbackService $feedbackService) {}

    public function store(StoreFeedbackRequest $request): FeedbackResource
    {
        $validatedData = $request->validated();
        $validatedData['image_path'] = $request->file('image_path');

        $feedback = $this->feedbackService->createFeedback($validatedData, JWTAuth::user()->id);

        return FeedbackResource::make($feedback);
    }

    public function show(int $id): FeedbackResource
    {
        $feedback = $this->feedbackService->getFeedback($id);
        return FeedbackResource::make($feedback);
    }

    public function update(UpdateFeedbackRequest $request, int $id): FeedbackResource
    {
        $validateData = $request->validated();

        $feedback = $this->feedbackService->updateFeedback($validateData, $id, JWTAuth::user()->id);

        return FeedbackResource::make($feedback);
    }

    public function destroy(int $id): Response
    {
        $this->feedbackService->deleteFeedback($id);
        return response()->noContent();
    }
}
