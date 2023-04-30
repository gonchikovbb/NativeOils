<?php

namespace App\Http\Controllers;

use App\Client\RMaslaClient;
use App\Models\Contact;
use App\Services\ContactService;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    private RMaslaClient $rMaslaClient;

    public function __construct(RMaslaClient $rMaslaClient)
    {
        $this->rMaslaClient = $rMaslaClient;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ;
    }

    public function update($id)
    {
        $contact = Contact::query()->find($id);

        return $contact->update($contact);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $contact = Contact::query()->find($id);

        $contact->delete();

        return response()->json(['message' => 'Contact deleted'],200);
    }
}
