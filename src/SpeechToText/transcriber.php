<?php
//header("Access-Control-Allow-Origin: http://localhost");

// AssemblyAI API endpoint (where we submit the file)
//$transcript_endpoint = "https://api.assemblyai.com/v2/transcript";

// Your API key
//$YOUR_API_KEY = "b4b5ebd676194efdadfe68e0ca0ee5a5";

// Request headers
/*$headers = array(
    "authorization: $YOUR_API_KEY",
    "content-type: application/json"
);*/

// Check if the audio file is received
/*if (isset($_FILES['audio'])) {
    // Get the recorded audio file from the request
    $audioData = file_get_contents($_FILES['audio']['tmp_name']);

    // Encode audio data to base64
    $base64Audio = base64_encode($audioData);

    // Prepare JSON data
    $jsonData = json_encode(array("audio_url" => "data:audio/webm;base64," . $base64Audio));

    // Submit the audio data for transcription via HTTP request
    $curl = curl_init($transcript_endpoint);
    
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);*/
    
    // Check if the API request was successful
   /* if ($response === false) {
        echo "Error submitting audio data for transcription.";
    } else {*/
        // Parse the API response
       // $response = json_decode($response, true);

        //if (isset($response['id'])) {
          //  $transcript_id = $response['id'];
            //$polling_endpoint = "https://api.assemblyai.com/v2/transcript/$transcript_id";

            // Poll for transcription completion
  //          while (true) {
    //            $polling_response = curl_init($polling_endpoint);
      //          curl_setopt($polling_response, CURLOPT_HTTPHEADER, $headers);
        //        curl_setopt($polling_response, CURLOPT_RETURNTRANSFER, true);
          //      $transcription_result = json_decode(curl_exec($polling_response), true);
//
  //              if (isset($transcription_result['status']) && $transcription_result['status'] === "completed") {
    //                echo $transcription_result['text'];
      //              break;
        //        } else if (isset($transcription_result['status']) && $transcription_result['status'] === "error") {
          //          throw new Exception("Transcription failed: " . $transcription_result['error']);
            //    } else {
              //      sleep(3);
                //}
            //}
        //} else {
         //   echo "Error: Unexpected response from the transcription API.";
        //}
    //}
//} else {
  //  echo "No audio file received.";
//}
//composer "repositories": [
      //  {
        //    "type": "vcs",
          //  "url": "https://github.com/kunalvarma05/dropbox-php-sdk"
        //}
    //],
?>
