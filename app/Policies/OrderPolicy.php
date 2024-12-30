<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function view(User $user, Order $order)
    {
        return $user->id === $order->user_id;
    }

    public function cancel(User $user, Order $order)
    {
        return $user->id === $order->user_id && $order->can_be_cancelled;
    }

    public function return(User $user, Order $order)
    {
        return $user->id === $order->user_id && $order->can_be_returned;
    }
}
