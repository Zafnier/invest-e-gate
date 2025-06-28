<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Vendor extends Model
{
    use HasFactory;

    // Define the table name explicitly (since it's not the plural of the model)
    protected $table = 'vendors';

    // Fillable attributes
    protected $fillable = [
        'name', 'business_name', 'contact_number', 'address', 
        'sec_registration', 'bir_registration', 'dti_registration'
    ];

    /**
     * Handle file upload and return the file path.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $fieldName
     * @return void
     */
    public function uploadFile($file, $fieldName)
    {
        // Check if the file is present
        if ($file) {
            // Generate a unique file name and store it in the vendor_files folder under the public disk
            $filePath = $file->store('vendor_files', 'public');
            
            // Update the corresponding field with the file path
            $this->update([$fieldName => $filePath]);
        }
    }

    /**
     * Optional: Add accessors to handle file paths more elegantly
     * For example, to access the full URL of a file stored in the 'public' disk:
     *
     * @param string $value
     * @return string
     */
    public function getSecRegistrationAttribute($value)
    {
        return Storage::url($value);
    }

    public function getBirRegistrationAttribute($value)
    {
        return Storage::url($value);
    }

    public function getDtiRegistrationAttribute($value)
    {
        return Storage::url($value);
    }

    // You can add similar methods to handle other fields or even delete files if needed
}
