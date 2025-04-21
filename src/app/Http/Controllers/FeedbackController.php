<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeedbackRequest;
use App\Http\Requests\UpdateFeedbackRequest;
use App\Services\FeedbackService;
use Tymon\JWTAuth\Facades\JWTAuth;

class FeedbackController extends Controller
{

    public function __construct(protected FeedbackService $feedbackService) {}

    public function index(int $product_id)
    {
        return $this->feedbackService->getAllFeedbacks($product_id);
    }

    public function store(StoreFeedbackRequest $request)
    {
        $validateData = $request->validated();

        $validateData['user_id'] = JWTAuth::user()->id;
        $validatedData['image_path'] = $request->file('image_path');

        $feedback = $this->feedbackService->createFeedback($validateData);

        if ($feedback instanceof \Illuminate\Http\JsonResponse) {
            return $feedback;
        }

        return response()->json([
            'message' => 'Successfully created!',
            'feedback' => $feedback
        ], 201);
    }

    public function show(int $id)
    {
        return response()->json($this->feedbackService->getFeedback($id), 200);
    }

    public function update(UpdateFeedbackRequest $request, int $id)
    {
        $validateData = $request->validated();

        $feedback = $this->feedbackService->updateFeedback($validateData, $id, JWTAuth::user()->id);

        if ($feedback instanceof \Illuminate\Http\JsonResponse) {
            return $feedback;
        }

        return response()->json([
            'message' => 'Successfully updated!',
            'feedback' => $feedback
        ], 200);
    }

    public function destroy(int $id)
    {
        $this->feedbackService->deleteFeedback($id);

        return response()->json([
            'message' => 'Successfully deleted!',
        ], 204);     
    }
}
