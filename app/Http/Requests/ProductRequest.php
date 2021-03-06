<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if (isset($this->product)) {
            return auth()->user()->can('edit_products');
        }

        return auth()->user()->can('create_products');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                isset($this->product) ? 'unique:products,name,' . $this->product->id : 'unique:products,name',
            ],
            'title' => 'nullable|string',
            'slug' => 'nullable|string',
            'manufacturer_id' => 'nullable|integer',
            'category_id' => 'required|integer',
            'publish' => 'nullable|string|in:on',
            'materials' => 'nullable|string',
            'description' => 'nullable|string',
            'modification' => 'nullable|string',
            'workingconditions' => 'nullable|string',
            'images_path' => 'nullable|string',
            'date_manufactured' => 'nullable|date_format:Y-m-d',
            'price' => 'nullable|numeric',
            'copy_img' => 'nullable|integer',
        ];
    }
}
