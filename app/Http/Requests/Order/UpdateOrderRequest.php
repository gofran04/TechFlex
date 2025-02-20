<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Order;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $order = Order::find($this->route()->parameters()['order']['id']);

        if ($order->status == 'initiated') {
            if (!in_array($this->status, ['in process','canceled'])) {
                return false;
            }
        }
        if ($order->status == 'in process') {
            if (!in_array($this->status, ['out to delivery'])) {
                return false;
            }
        }
        if ($order->status == 'out to delivery') {
            if (!in_array($this->status, ['delivered'])) {
                return false;
            }
        }
        if ($order->status == 'canceled') {
            if (!in_array($this->status, ['initiated'])) {
                return false;
            }
        }
        abort_if($order->status == 'delivered', '401', ('You Can Not Update This Order Status !'));
       
        return true;   
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'status'    => ['required', Rule::in(['initiated','in process','out to delivery','delivered','canceled'])],
            'driver_id' => [Rule::requiredIf($this->status == 'initiated'),'exists:users,id'],
        ];
    }
}
