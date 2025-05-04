<?php

namespace App\Services;

use App\Exceptions\AccessDeniedException;
use App\Exceptions\AlreadyGivenFeedbackException;
use App\Exceptions\DelivereFeedbackException;
use App\Repositories\FeedbackRepositories;
use App\Repositories\OrdersRepositories;
use App\Repositories\UserRepositories;
use Illuminate\Support\Facades\Storage;
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
        return $this->feedbackRepositories->getAllFeedbacks($product_id);
    }

    public function createFeedback(array $data)
    {
        $userPurchasedTheProduct = $this->feedbackRepositories->userHasOrder($data);
        if (!$userPurchasedTheProduct) {
            throw new AccessDeniedException();
        }

        $userHasAlreadyGivenFeedback = $this->feedbackRepositories->userHasAlreadyGivenFeedback($data['user_id'], $data['product_id']);
        if ($userHasAlreadyGivenFeedback) {
            throw new AlreadyGivenFeedbackException();
        }

        if (!empty($data['image_path'])) {
            $image = $data['image_path'];
            $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $path = Storage::putFileAs('public/feedback', $image, $imageName);
            $data['image_path'] = $path;
        } else {
            unset($data['image_path']);
        }

        if ($userPurchasedTheProduct->status !== 'completed') {
            throw new DelivereFeedbackException();
        }

        $feedback = $this->feedbackRepositories->createFeedback($data);

        return  $feedback;
    }

    public function getFeedback(int $id)
    {
        return $this->feedbackRepositories->getFeedback($id);
    }

    public function updateFeedback(array $data, int $id, int $user_id)
    {
        $feedbackUser = $this->feedbackRepositories->getFeedback($id);
        if ($user_id != $feedbackUser->user_id) {
            throw new AccessDeniedException();
        }

        return $this->feedbackRepositories->updateFeedback($data, $id);
    }

    public function deleteFeedback(int $id)
    {
        $this->feedbackRepositories->getFeedback($id);
        return $this->feedbackRepositories->deleteFeedback($id);
    }
}
