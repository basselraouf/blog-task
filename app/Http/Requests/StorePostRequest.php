<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'pinned' => 'required|boolean',
            'tags' => 'sometimes|array',
            'tags.*' => 'exists:tags,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The title is required.',
            'body.required' => 'The body content is required.',
            'cover_image.required' => 'A cover image is required.',
            'cover_image.image' => 'The cover image must be a valid image file.',
            'pinned.required' => 'Pinned status is required.',
        ];
    }
}
