<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;

use App\Models\Project;
use App\Models\Client;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? 10;
        $clients = Client::orderBy('created_at', 'desc')->paginate($perPage);

        return response($clients);
    }

    public function store(StoreClientRequest $request, Project $project)
    {
        $client                     = new Client;
        $client->user_id            = request()->user->id;
        $client->customer_type      = $request->customer_type;
        $client->gender_type        = $request->gender_type;
        $client->middle_name        = $request->middle_name;
        $client->surname            = $request->surname;
        $client->email              = $request->email;
        $client->phone              = $request->phone;
        $client->language           = $request->language;
        $client->billing_address    = $request->billing_address;
        $client->exterior           = $request->exterior;
        $client->number             = $request->number;
        $client->zip_code           = $request->zip_code;
        $client->place              = $request->place;
        $client->country            = $request->country;
        $client->private_telephone  = $request->private_telephone;
        $client->business_telephone = $request->business_telephone;
        $client->addFlag(Client::FLAG_ACTIVE);
        if ($client->save()) {
            $project->update(['client_id' => $client->id]);
            return response('Client created successfully');
        }
        return response('Client did not create!', 400);
    }

    public function show(Client $client)
    {
        if ($client) return response($client);
        return response('No records found', 400);
    }

    public function update(UpdateClientRequest $request, Project $project, Client $client)
    {
        $client->customer_type      = $request->input('customer_type', $client->customer_type);
        $client->gender_type        = $request->input('gender_type', $client->gender_type);
        $client->middle_name        = $request->input('middle_name', $client->middle_name);
        $client->surname            = $request->input('surname', $client->surname);
        $client->phone              = $request->input('phone', $client->phone);
        $client->language           = $request->input('language', $client->language);
        $client->billing_address    = $request->input('billing_address', $client->billing_address);
        $client->exterior           = $request->input('exterior', $client->exterior);
        $client->number             = $request->input('number', $client->number);
        $client->zip_code           = $request->input('zip_code', $client->zip_code);
        $client->place              = $request->input('place', $client->place);
        $client->country            = $request->input('country', $client->country);
        $client->private_telephone  = $request->input('private_phone', $client->private_telephone);
        $client->business_telephone = $request->input('business_phone', $client->business_telephone);
        $client->updateFlag(Client::FLAG_ACTIVE, $request->status);
        if ($client->save()) {
            $project->update(['client_id' => $client->id]);
            return response('Client updated successfully');
        }
        return response('Client did not update', 400);
    }

    public function destroy(Client $client)
    {
        if ($client->delete()) return response('client deleted successfully');
        return response('Client did not delete', 400);
    }
}
