
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>
#include <ESP8266WiFi.h>
#include <ArduinoJson.h>


const char* ssid = "Sandoval_Wifi+";
const char* password = "ARCS1153";
const char* serverIP = "192.168.5.227";
const int serverPort = 80;

//-------------------------------------------------------------------------------------------------------------------

 //--> Create a WiFiClientSecure object.

void setup() {

  // put your setup code here, to run once:
  Serial.begin(9600);

  Serial.println();
  Serial.print("Connecting to WiFi ");
  Serial.print(ssid);

  WiFi.begin(ssid, password);

  Serial.println();

  Serial.print("Connecting");

  while(WiFi.status() != WL_CONNECTED ){
    delay(1000);
    Serial.println(".");
  }

  Serial.print("NodeMCU IP Address: ");
  Serial.println(WiFi.localIP());
  delay(3000);

}

void loop() {

  if(Serial.available()>0) {
    String jsonString = Serial.readStringUntil('\n');
    Serial.print("Received JSON: ");
    Serial.println(jsonString);

    // Process the received JSON data using ArduinoJson library
    StaticJsonDocument<200> jsonDoc; // Adjust the buffer size as needed
    DeserializationError err = deserializeJson(jsonDoc, jsonString);

    if (err == DeserializationError::Ok) {
      // Extract the sensor data from the JSON object
      int gasSensorValue = jsonDoc["Gas Sensor"];
      int smokeSensorValue = jsonDoc["Smoke Sensor"];
      String waterLevel = jsonDoc["Water Level"];

      // Create a JSON object to hold the sensor data
      StaticJsonDocument<200> sensorDataJson;
      sensorDataJson["Gas Sensor"] = gasSensorValue;
      sensorDataJson["Smoke Sensor"] = smokeSensorValue;
      sensorDataJson["Water Level"] = waterLevel;
      

      // Serialize the JSON object into a string
      String sensorDataJsonStr;
      delay(2000);
      serializeJson(sensorDataJson, sensorDataJsonStr);

      // Send the sensor data JSON to the server
      sendDataToServer(sensorDataJsonStr);
    } else {
      Serial.println("JSON parsing failed.");
    }

  }
  delay(10000);
}

//FUNCTIONS------------------------------------------------------------------------------------------------------------------------------------

void sendDataToServer(const String& jsonStr) {
  HTTPClient http;
  WiFiClient client;

  if (http.begin(client, "http://192.168.5.227/server2.php")) {
    http.addHeader("Content-Type", "application/json");
    int httpResponseCode = http.POST(jsonStr);
    String payload = http.getString();
      Serial.println(payload);

    if (httpResponseCode == HTTP_CODE_OK) {
      Serial.println("Data sent successfully");
    } else {
      String payload = http.getString();
      Serial.println(payload);
      Serial.print("HTTP POST failed, error code: ");
      Serial.println(httpResponseCode);
    }
    http.end();
  } else {
    Serial.println("Unable to connect to the server");
  }
}
