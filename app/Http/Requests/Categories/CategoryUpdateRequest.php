<?php

namespace App\Http\Requests\Categories;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class CategoryUpdateRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:250',
            'description' => 'required|max:250',
        ];
    }

    public function getValidRequest()
    {
        $image = $this->file('image');
        $slug = Str::slug($this->input('name'));

        $imageExt = $image->getClientOriginalExtension();
        $allowedExt = ['.jpg','.jpeg','.png','bmp'];

        if (! in_array($imageExt, $allowedExt)) {
            if (isset($image)) {

                $currentDate = Carbon::now()->toDateString();
                $imageName = $slug . '-' . $currentDate . '-' . uniqid() . '.' . $imageExt;

                if (!Storage::disk('public')->exists('category')) {
                    Storage::disk('public')->makeDirectory('category');
                }
                if (!Storage::disk('public')->exists('category/thumbnail')) {
                    Storage::disk('public')->makeDirectory('category/thumbnail');
                }

                $path = "images/";

                $postImage = Image::make($image)->fit(1060, 1060)
                    ->save(public_path($path).'category/'.$imageName);

                $thumbnail = Image::make($image)->fit(550, 550)
                    ->save(public_path($path).'category/thumbnail/'.$imageName);

            } else {
                $imageName = $this->input('image');
            }
        }

        return [
            'name' => $this->input('name'),
            'slug' => $slug,
            'description' => $this->input('description'),
            'image' => $imageName
        ];
    }
}
