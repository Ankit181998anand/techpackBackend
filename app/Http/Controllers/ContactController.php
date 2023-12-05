<?php

namespace App\Http\Controllers;
use App\Models\Contact;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    //
    public function insertContact(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone_no' => 'required|string',
            'query' => 'required|string',
        ]);

        $contact = Contact::create($data);

        return response()->json(['message' => 'Contact created successfully', 'contact' => $contact], 201);
    }

    public function getAllContacts()
    {
        $contacts = Contact::all();

        return response()->json([$contacts]);
    }

    public function deleteContact($id)
    {
        try {
            $contact = Contact::findOrFail($id);
            $contact->delete();

            return response()->json(['message' => 'Contact deleted successfully']);
        } catch (\Exception $e) {
            \Log::error('Delete Contact Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete contact.'], 500);
        }
    }
}
