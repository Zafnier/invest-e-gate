<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'business_name', 'contact_number', 'address', 
        'sec_registration', 'bir_registration', 'dti_registration'
    ];

    /**
     * Handle file upload and return the file path with custom naming convention.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string  $fieldName
     * @return void
     */
    public function uploadFile($file, $fieldName)
    {
        // Check if file is present
        if ($file) {
            // Generate the custom file name based on business name and field name
        $extension = $file->getClientOriginalExtension();
        $filename = strtolower($this->business_name) . '_' . $fieldName . '.' . $extension;

        // Define the custom public path
        $destinationPath = 'public/backend/images/vendors';
        $filePath = $destinationPath . '/' . $filename;

        // Move the file to the custom folder path
        $file->move(public_path('backend/images/vendors'), $filename);


            // Update the model with the file path
            $this->update([$fieldName => $filePath]);
        }
    }

    /**
     * Upload the SEC registration file.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @return void
     */
    public function uploadSecRegistration($file)
    {
        $this->uploadFile($file, 'sec_registration');
    }

    /**
     * Upload the BIR registration file.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @return void
     */
    public function uploadBirRegistration($file)
    {
        $this->uploadFile($file, 'bir_registration');
    }

    /**
     * Upload the DTI registration file.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @return void
     */
    public function uploadDtiRegistration($file)
    {
        $this->uploadFile($file, 'dti_registration');
    }
}
