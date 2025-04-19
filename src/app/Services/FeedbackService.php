<?php

namespace App\Services;

use App\Repositories\FeedbackRepositories;

class FeedbackService
{

    public function __construct(protected FeedbackRepositories $feedbackRepositories) {}

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
