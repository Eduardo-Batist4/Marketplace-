<?php

namespace App\Services;

use App\Exceptions\AccessDeniedException;
use App\Exceptions\AlreadyGivenFeedbackException;
use App\Exceptions\ProductNotDeliveredException;
use App\Models\Feedback;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FeedbackService
{
    public function createFeedback(array $data, int $userId): Feedback
    {
        $data['user_id'] = $userId;

        $this->validateFeedbackCreation($data, $userId);

        $data['image_path'] = $this->uploadImage($data['image_path'] ?? null);

        return Feedback::create($data);
    }

    private function validateFeedbackCreation (array $data, int $userId): void
    {
        $order = Order::where('user_id', $userId)
        ->whereHas('orderItems', function ($query) use ($data) {
            $query->where('product_id', $data['product_id']);
            })->first();

        if (!$order) {
            throw new AccessDeniedException();
        }

        if ($order->status !== 'completed') {
            throw new ProductNotDeliveredException();
        }

        $alreadyGivenFeedback = Feedback::where('user_id', $userId)
            ->where('product_id', $data['product_id'])
            ->exists();

        if ($alreadyGivenFeedback) {
            throw new AlreadyGivenFeedbackException();
        }
    }

    private function uploadImage (?object $image): string|null
    {
        if(!$image) {
            return null;
        }

        $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
        return Storage::putFileAs('public/feedback', $image, $imageName);
    }

    public function getFeedback(int $id)
    {
        return Feedback::findOrFail($id)->load('product');
    }

    public function updateFeedback(array $data, int $id, int $user_id): Feedback
    {
        $feedback = Feedback::findOrFail($id);
        if ($user_id != $feedback->user_id) {
            throw new AccessDeniedException();
        }

        $feedback->update($data);
        return $feedback->fresh();
    }

    public function deleteFeedback(int $id): bool
    {
        $feedback = Feedback::findOrFail($id);
        return $feedback->delete();
    }
}
