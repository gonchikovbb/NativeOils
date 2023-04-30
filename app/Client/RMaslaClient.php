<?php

namespace App\Client;

use App\Http\Requests\StoreUpdateContactRequest;
use GuzzleHttp\Client;

class RMaslaClient
{
    protected Client $contact;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function login()
    {
        $response = $this->client->request('GET', 'http://sales.rmasla.ru/webservice.php?operation=getchallenge&username=admin');

        $token = json_decode($response->getBody()->getContents(),true)['result']['token'];

        $response = $this->client->request('POST', 'http://sales.rmasla.ru/webservice.php', [
            'form_params' => [
                'operation' => 'login',
                'username' => 'admin',
                'accessKey' => md5($token . 'nGCIkrKX2kSSbvlW'),
            ],
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new RMaslaClientException('Получен код, не 200. Response body: '. $response->getBody()->getContents());
        }

        return json_decode($response->getBody()->getContents(),true);
    }

    public function getContacts()
    {
        $sessionName = $this->login()['result']['sessionName'];
        $userId = $this->login()['result']['userId'];

        $response = $this->client->request('GET', "http://sales.rmasla.ru/webservice.php?query=SELECT%20%2A%20FROM%20Contacts%3B&operation=query&sessionName=$sessionName&id=$userId");

        return $response->getBody()->getContents();
    }

    public function updateContact(StoreUpdateContactRequest $request, $id)
    {
        $data = $request->validated();

        $sessionName = $this->login()['result']['sessionName'];
        $userId = $this->login()['result']['userId'];

        $firstName = $data['first_name'];
        $lastName = $data['last_name'];

        $jsonData = json_encode([
            'firstname' => $firstName,
            'lastname' => $lastName,
            'assigned_user_id' => $userId,
            'id' => $id
        ],true);

        $response = $this->client->request('POST', "http://sales.rmasla.ru/webservice.php", [
            'form_params' => [
                'operation' => 'update',
                'sessionName' => $sessionName,
                'element' => $jsonData,
            ],
        ]);

        return $response->getBody()->getContents();
    }

    public function deleteContact($id)
    {
        $sessionName = $this->login()['result']['sessionName'];

        $response = $this->client->request('POST', "http://sales.rmasla.ru/webservice.php", [
            'form_params' => [
                'operation' => 'delete',
                'sessionName' => $sessionName,
                'id' => $id,
            ],
        ]);

        return response()->json(['message' => 'Contact deleted'],200);
    }
}
