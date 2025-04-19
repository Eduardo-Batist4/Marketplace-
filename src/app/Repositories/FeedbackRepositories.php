<?php

namespace App\Repositories;

use App\Models\Feedback;

class FeedbackRepositories
{
    public function getAllFeedbacks()
    {
        return Feedback::all();
    }

    public function createFeedback(array $data)
    {
        return Feedback::create($data);
    }

    public function getFeedback(int $id)
    {
        return Feedback::findOrFail($id);
    }

    public function updateFeedback(array $data, int $id)
    {
        $feedback = $this->getFeedback($id);

        $feedback->update($data);
        return $feedback;
    }

    public function deleteFeedback(int $id)
    {
        return Feedback::destroy($id);
    }
}
