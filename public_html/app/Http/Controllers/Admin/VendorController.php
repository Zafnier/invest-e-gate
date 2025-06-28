<?php

namespace App\Http\Controllers\Admin;

use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class VendorController extends Controller
{
    /**
     * Show the list of vendors.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch all vendors with pagination
        $vendors = Vendor::paginate(10);
        
        // Return the index view with vendors data
        return view('admin.sections.vendors.index', compact('vendors'));
    }

    /**
     * Show the vendor registration form.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Return the view for creating a new vendor
        return view('admin.sections.vendors.create');
    }

    /**
     * Store a newly created vendor.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'business_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'sec_registration' => 'required|file|mimes:pdf,jpg,png,jpeg|max:10240',
            'bir_registration' => 'required|file|mimes:pdf,jpg,png,jpeg|max:10240',
            'dti_registration' => 'required|file|mimes:pdf,jpg,png,jpeg|max:10240',
        ]);

        // Create the vendor instance
        $vendor = Vendor::create([
            'name' => $request->name,
            'business_name' => $request->business_name,
            'contact_number' => $request->contact_number,
            'address' => $request->address,
        ]);

        // Handle file uploads and save their paths
        $this->handleFileUpload($request, $vendor);

        // Redirect to vendor list with success message
        return redirect()->route('admin.vendors.index')->with('success', 'Vendor registered successfully!');
    }

    /**
     * Show the vendor editing form.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Fetch the vendor data
        $vendor = Vendor::findOrFail($id);

        // Return the edit view with the vendor data
        return view('admin.sections.vendors.edit', compact('vendor'));
    }

    /**
     * Update the vendor information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'business_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'sec_registration' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:10240',
            'bir_registration' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:10240',
            'dti_registration' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:10240',
        ]);

        // Find the vendor to update
        $vendor = Vendor::findOrFail($id);

        // Update vendor details
        $vendor->update([
            'name' => $request->name,
            'business_name' => $request->business_name,
            'contact_number' => $request->contact_number,
            'address' => $request->address,
        ]);

        // Handle file uploads for updated vendor
        $this->handleFileUpload($request, $vendor);

        // Redirect to vendor list with success message
        return redirect()->route('admin.vendors.index')->with('success', 'Vendor updated successfully!');
    }

    /**
     * Delete the vendor.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Find the vendor by ID
        $vendor = Vendor::findOrFail($id);

        // Delete associated files from storage
        if ($vendor->sec_registration) {
            Storage::delete($vendor->sec_registration);
        }
        if ($vendor->bir_registration) {
            Storage::delete($vendor->bir_registration);
        }
        if ($vendor->dti_registration) {
            Storage::delete($vendor->dti_registration);
        }

        // Delete the vendor
        $vendor->delete();

        // Redirect to vendor list with success message
        return redirect()->route('admin.vendors.index')->with('success', 'Vendor deleted successfully!');
    }

    /**
     * Handle file uploads for vendor registration or updates.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\Vendor  $vendor
     * @return void
     */
     /**
 * Handle file uploads for vendor registration or updates.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  \App\Models\Admin\Vendor  $vendor
 * @return void
 */
private function handleFileUpload(Request $request, Vendor $vendor)
{
    $destinationPath = public_path('backend/images/vendors');

    // Handle SEC Registration upload if present
    if ($request->hasFile('sec_registration')) {
        if ($vendor->sec_registration) {
            @unlink(public_path($vendor->sec_registration));
        }
        $filename = strtolower($vendor->business_name) . '_sec_registration.' . $request->file('sec_registration')->getClientOriginalExtension();
        $request->file('sec_registration')->move($destinationPath, $filename);
        $vendor->sec_registration = 'backend/images/vendors/' . $filename;
    }

    // Handle BIR Registration upload if present
    if ($request->hasFile('bir_registration')) {
        if ($vendor->bir_registration) {
            @unlink(public_path($vendor->bir_registration));
        }
        $filename = strtolower($vendor->business_name) . '_bir_registration.' . $request->file('bir_registration')->getClientOriginalExtension();
        $request->file('bir_registration')->move($destinationPath, $filename);
        $vendor->bir_registration = 'backend/images/vendors/' . $filename;
    }

    // Handle DTI Registration upload if present
    if ($request->hasFile('dti_registration')) {
        if ($vendor->dti_registration) {
            @unlink(public_path($vendor->dti_registration));
        }
        $filename = strtolower($vendor->business_name) . '_dti_registration.' . $request->file('dti_registration')->getClientOriginalExtension();
        $request->file('dti_registration')->move($destinationPath, $filename);
        $vendor->dti_registration = 'backend/images/vendors/' . $filename;
    }

    // Save the updated vendor data
    $vendor->save();
}

}
