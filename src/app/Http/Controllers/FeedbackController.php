<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeedbackRequest;
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

        $feedback = $this->feedbackService->createFeedback($validateData);

        return response()->json($feedback, 201);
    }

}
