<?php

namespace App\Http\Requests;

use Helper\ResponseHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use StatusEnum;

class TasksRequest extends FormRequest
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

    protected function failedValidation(Validator $validator){
        $type = '';
        $message = '';
        foreach ($validator->errors()->messages() as $key => $value) {
            $type = "VALIDATION_ERROR_ON_".strtoupper($key);
            $message = $value;
        }

        return ResponseHelper::UnprocessableEntityReponse($type, true, $message);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "title" => ["required", "string"],
            "description" => ["string"],
            "status" => ["required", "in:pending,in_progress,completed"]
        ];
    }

    public function attributes()
    {
        return [
            "title" => "Judul",
            "description" => "Deskripsi",
            "status" => "Status",
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Wajib menginputkan :attribute.',
            "string" => ":attribute harus bertipe teks.",
            "in" => "Pilihan :attribute tidak valid."
        ];
    }
}
