<?php

namespace App\Services;

use App\Repositories\FeedbackRepositories;
use App\Repositories\OrdersRepositories;
use App\Repositories\UserRepositories;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tymon\JWTAuth\Facades\JWTAuth;

class FeedbackService
{

    public function __construct(
        protected FeedbackRepositories $feedbackRepositories,
        protected UserRepositories $userRepositories,
        protected OrdersRepositories $ordersRepositories
    ) {}

    public function getAllFeedbacks()
    {
        try {
            return $this->feedbackRepositories->getAllFeedbacks();
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function createFeedback(array $data)
    {
        try {
            $userPurchasedTheProduct = $this->feedbackRepositories->userHasOrder($data);
            if (!$userPurchasedTheProduct) {
                return throw new HttpException(403, 'Not permission.');
            }

            $userHasAlreadyGivenFeedback = $this->feedbackRepositories->userHasAlreadyGivenFeedback($data['product_id']);
            if ($userHasAlreadyGivenFeedback) {
                return throw new HttpException(403, 'You have already given feedback.');
            }

            $feedback = $this->feedbackRepositories->createFeedback($data);

            return $feedback;
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function getFeedback(int $id)
    {
        try {
            return $this->feedbackRepositories->getFeedback($id);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function updateFeedback(array $data, int $id)
    {
        try {
            return $this->feedbackRepositories->updateFeedback($data, $id);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function deleteFeedback(int $id)
    {
        try {
            $this->feedbackRepositories->getFeedback($id);
            return $this->feedbackRepositories->deleteFeedback($id);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }
}
