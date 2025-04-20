<?php

namespace App\Repositories;

use App\Models\Feedback;
use App\Models\Order;

class FeedbackRepositories
{
    public function getAllFeedbacks(int $product_id)
    {
        return Feedback::where('product_id', $product_id)->get();
    }

    public function userHasOrder(array $data)
    {
        $product_id = $data['product_id'];

        $order = Order::where('user_id', $data['user_id'])->whereHas('orderItems', function ($query) use ($product_id) {
            $query->where('product_id', $product_id);
        })->with('orderItems')->exists();

        return $order;
    }

    public function userHasAlreadyGivenFeedback(int $user_id, int $product_id)
    {
        return Feedback::where('user_id', $user_id)->where('product_id', $product_id)->first();
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
