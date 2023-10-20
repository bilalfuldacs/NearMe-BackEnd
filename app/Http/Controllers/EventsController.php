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
            'street' => $event['Street'] ,
            'postalCode' => $event['PostalCode'],
            'phone' => $event['Phone'],
            'email' => $event['Email'],
            'whatsapp' => $event['Whatsapp'],
            'Hausnumber' => $event['Hausnumber'],
            'eventDescription' => $event['EventDescription'],
            'user_id'=>$event['user_id'],
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
        Log::info('Deleting event with ID:', ['event_id' => $request->all()]);
        // Transform the data to match the database column names
        $transformedData = [
            'Name' => $requestData['eventName'],
            'Street' => $requestData['street'],
            'Type' => $requestData['eventType'],
            'Location' => $requestData['eventLocation'],
            'Gender' => $requestData['preferredGender'],
            'TotalPeople' => $requestData['totalPeople'],
            'FromDate' => $requestData['fromDate'],
            'ToDate' => $requestData['toDate'],
            'AgeGroup' => $requestData['ageGroup'],
            'Country' => $requestData['country'],
            'City' => $requestData['city'],
            'Hausnumber' => $requestData['Hausnumber'],
            'PostalCode' => $requestData['postalCode'],
            'EventDescription' => $requestData['eventDescription'],
            'Email' => $requestData['email'],
            'Phone' => $requestData['phone'],
            'Whatsapp' => $requestData['whatsapp'],
            'user_id' =>auth()->user()->id, // You might need to adjust this based on your authentication
        ];
        
        // Insert the transformed data into the database

       $Event= Event::create($transformedData);
       $images = $requestData['pictures'];
   
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

    public function deleteEvent($id)
{
    try {
        // Find the event by ID
        $event = Event::findOrFail($id);
        Log::info('Deleting event with ID:', ['event_id' => $event->user_id]);
        Log::info('Deleting event with ID:', ['event_id' => $event]);
        // Check if the authenticated user is the owner of the event
        if (auth()->user()->id !== $event->user_id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        Log::info('Deleting event with ID:', ['event_id' => $event]);
        // Delete associated images
        EventImages::where('event_id', $event->id)->delete();

        // Delete the event
        $event->delete();
        Log::info('Deleting event with ID:', ['event_id' => $id]);
        return response()->json(['message' => 'Event deleted successfully']);
    } catch (\Exception $e) {
        // Handle exceptions, log errors, etc.
        return response()->json(['error' => 'Error deleting event'], 500);
    }
}


    public function update(Request $request, $id)
    {
        // Find the existing event by ID
        $event = Event::findOrFail($id);
        Log::info('Deleting event with ID:', ['event_id' => $event]);
        $requestData = $request->all();

        // Transform the data to match the database column names
        $transformedData = [
            'Name' => $requestData['eventName'],
            'Street' => $requestData['street'],
            'Type' => $requestData['eventType'],
            'Location' => $requestData['eventLocation'],
            'Gender' => $requestData['preferredGender'],
            'TotalPeople' => $requestData['totalPeople'],
            'FromDate' => $requestData['fromDate'],
            'ToDate' => $requestData['toDate'],
            'AgeGroup' => $requestData['ageGroup'],
            'Country' => $requestData['country'],
            'City' => $requestData['city'],
            'Hausnumber' => $requestData['Hausnumber'],
            'PostalCode' => $requestData['postalCode'],
            'EventDescription' => $requestData['eventDescription'],
            'Email' => $requestData['email'],
            'Phone' => $requestData['phone'],
            'Whatsapp' => $requestData['whatsapp'],
            'user_id' => auth()->user()->id, // You might need to adjust this based on your authentication
        ];

        // Update the event with the transformed data
        $event->update($transformedData);

        // Handle images separately for update
        $images = $requestData['pictures'];

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
                'event_id' => $event->id, // Associate with the event
                'image_path' => $binary64Image, // Store the image path
            ]);
        }

        return response()->json(['message' => 'Event updated successfully', 'data' => $event]);
    }

    // ... other methods ...
}


