<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateShortUrlRequest extends FormRequest
{
    public function rules()
    {
        return [
            'url' => 'required|string|url',
            'life_time' => 'integer|nullable',
        ];
    }
}
