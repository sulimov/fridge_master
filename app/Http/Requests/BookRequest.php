<?php

namespace App\Http\Requests;

use App\Models\BookedBlocks;
use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $dateTo =  date('Y-m-d', strtotime($this->date_from . ' +' . BookedBlocks::MAX_STORAGE_DAYS . ' days'));

        return [
            'location_id' => 'required|integer|exists:locations,id',
            'temperature' => 'required|integer',
            'volume' => 'required|integer',
            'date_from' => 'required|date|after_or_equal:today',
            'date_to' => 'required|date|after_or_equal:date_from|before_or_equal:' . $dateTo,
            'name' => 'required|string',
            'email' => 'required|email',
        ];
    }
}
