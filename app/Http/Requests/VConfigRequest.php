<?php

namespace App\Http\Requests;

use App\Enums\OperatorEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class VConfigRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'=>'required|min:3',
            'server_id'=>'required|filled|exists:servers,id',
            'user_id'=>'required|filled|exists:users,id',
            'used'=>'nullable|numeric',
            'status'=>'required|boolean',
            'operator'=>'required|in'.implode(',',OperatorEnum::values()),
            'expire'=>'nullable|date',
        ];
    }
}
