<?php

namespace App\Services;

use App\Repositories\FeedbackRepositories;
use App\Repositories\OrdersRepositories;
use App\Repositories\UserRepositories;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Str;

class FeedbackService
{

    public function __construct(
        protected FeedbackRepositories $feedbackRepositories,
        protected UserRepositories $userRepositories,
        protected OrdersRepositories $ordersRepositories
    ) {}

    public function getAllFeedbacks(int $product_id)
    {
        try {
            return $this->feedbackRepositories->getAllFeedbacks($product_id);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function createFeedback(array $data)
    {
        try {
            $userPurchasedTheProduct = $this->feedbackRepositories->userHasOrder($data);
            if (!$userPurchasedTheProduct) {
                throw new HttpException(403, 'Not permission.');
            }

            $userHasAlreadyGivenFeedback = $this->feedbackRepositories->userHasAlreadyGivenFeedback($data['user_id'], $data['product_id']);
            if ($userHasAlreadyGivenFeedback) {
                throw new HttpException(403, 'You have already given feedback.');
            }

            // Verify if image is null
            if (!empty($data['image_path'])) {
                $image = $data['image_path'];
                $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
                $path = Storage::putFileAs('public/feedback', $image, $imageName);
                $data['image_path'] = $path;
            } else {
                unset($data['image_path']);
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

    public function updateFeedback(array $data, int $id, int $user_id)
    {
        try {
            $feedbackUser = $this->feedbackRepositories->getFeedback($id);
            if ($user_id != $feedbackUser->user_id) {
                throw new HttpException(403, 'Not permission.');
            }

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
