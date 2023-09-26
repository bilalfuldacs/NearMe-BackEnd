<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventImages;
use Illuminate\Support\Facades\Log; 
use Auth;
use Illuminate\Support\Facades\Storage;
class EventsController extends Controller
{
    //
    public function transformEventData($event)
    {
        return [
            'id' => $event['id'],
            'eventName' => $event['Name'],
            'eventType' => $event['Type'],
            'eventLocation' => $event['Location'],
            'preferredGender' => $event['Gender'],
            'totalPeople' => $event['TotalPeople'],
            'fromDate' => $event['FromDate'],
            'toDate' => $event['ToDate'],
            'ageGroup' => $event['AgeGroup'],
            'country' => $event['Country'],
            'city' => $event['City'],
            'street' => $event['State'] ?? 'N/A',
            'postalCode' => $event['PostalCode'],
            'phone' => $event['Phone'],
            'email' => $event['Email'],
            'whatsapp' => $event['Whatsapp'],
            'Hausnumber' => $event['Hausnumber'],
            'eventDescription' => $event['EventDescription'],
            'pictures' =>$event ['images'], 
        ];
    }
    public function Myevents(){
       
    
        $eventData = Event::where('user_id', auth()->user()->id)->with('images')->get();
        $transformedEvents = $eventData->map(function ($event) {
            return $this->transformEventData($event->toArray());
        });
return response()->json(['message' => 'User registered successfully', 'EventData' => $transformedEvents]);


    }
    public function Allevents(){
        $eventData = Event::with('images')->get();
        $transformedEvents = $eventData->map(function ($event) {
            return $this->transformEventData($event->toArray());
        });
return response()->json(['message' => 'User registered successfully', 'EventData' => $transformedEvents]);


    }
    public function store(Request $request){
       

        $requestData = $request->all();
     
        // Transform the data to match the database column names
        $transformedData = [
            'Name' => $requestData['eventName'],
            'Type' => $requestData['eventType'],
            'Location' => $requestData['eventLocation'],
            'Gender' => $requestData['preferredGender'],
            'TotalPeople' => $requestData['totalPeople'],
            'FromDate' => $requestData['fromDate'],
            'ToDate' => $requestData['toDate'],
            'AgeGroup' => $requestData['ageGroup'],
            'Country' => $requestData['country'],
            'City' => $requestData['city'],
            'Hausnumber' => $requestData['hausnummer'],
            'PostalCode' => $requestData['postalCode'],
            'EventDescription' => $requestData['eventDescription'],
            'Email' => $requestData['email'],
            'Phone' => $requestData['phone'],
            'Whatsapp' => $requestData['whatsapp'],
            'user_id' =>auth()->user()->id, // You might need to adjust this based on your authentication
        ];
        
        // Insert the transformed data into the database

       $Event= Event::create($transformedData);
       $images = $requestData['images'];
     Log::info('Incoming Registration Request Data: ' . $Event);
     foreach ($images as $imageData) {
        $originalFilename = $imageData['file']['path']; // Get the original filename
    
        // Generate a unique filename using the original filename, timestamp, and a unique identifier
        $uniqueFilename = time() . '_' . uniqid() . '_' . $originalFilename;
    
        // Construct the full file path including the 'public/images' directory
        $fullFilePath = 'public/images/' . $uniqueFilename;
    
        // Store the image using the constructed file path
        $imagePath = Storage::put($fullFilePath, $uniqueFilename);
        $binary64Image = $imageData['base64']; 
        // Create an EventImage record and associate it with the event
        EventImages::create([
            'event_id' => $Event->id, // Associate with the event
            'image_path' => $binary64Image,    // Store the image path
        ]);
    }
    
    }
}
