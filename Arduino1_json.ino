#include <ArduinoJson.h>
#include <SoftwareSerial.h>

#define GSM_RX_PIN 10    // GSM module RX pin
#define GSM_TX_PIN 11    // GSM module TX pin#define SmokeMQ2pin (1)
#define smokeSensorPin (1)
#define waterSensorPower 7
#define waterSensorPin A2

int smokeSensorValue; // smoke val

int gasSensorPin=A0;
int gasSensorData;
float gasSensorValue; // gas val

// Water Sensor Variables 
int lowerThreshold = 420;
int upperThreshold = 520;
int water_val = 0;  // Value for storing water level

bool smokeDetected = false;
bool gasDetected = false;
bool waterDetected = false;

// Alert Time Interval Variables
unsigned long lastAlertTime = 0;
const unsigned long alertInterval = 10000;  // 1 min in milliseconds

// GSM PINS
SoftwareSerial gsmSerial(10, 11);
SoftwareSerial espSerial(2, 3);

void setup() {
  // put your setup code here, to run once:
  Serial.begin(9600);
  //smk.begin

  //Gas Sensor Variables----------------------------------------------------------------------------------------------------------------
  pinMode(gasSensorPin,INPUT); 

  //Water Sensor Variables--------------------------------------------------------------------------------------------------------------
	pinMode(waterSensorPower, OUTPUT);
	digitalWrite(waterSensorPower, LOW);

}

void loop() {

  // Sensor readings
  gasSensorData = analogRead(gasSensorPin);
    // if(gasSensorData > 200){
    //   if (millis() - lastAlertTime >= alertInterval) {
    //     testSMS("GAS ALERT SMS");
    //     gasDetected = true;
    //     lastAlertTime = millis();
    //   }
    // }

  smokeSensorValue = analogRead(smokeSensorPin);
  // if(smokeSensorValue >= 200){
  //   if (millis() - lastAlertTime >= alertInterval) {
  //     testSMS("SMOKE ALERT SMS");
  //     smokeDetected = true;
  //     lastAlertTime = millis();
  //     }    
  // }

  int waterLevel = readSensor();

  // Create a JSON object
  StaticJsonDocument<200> jsonDoc; // Adjust the buffer size as needed

  // Add sensor readings to the JSON object
  jsonDoc["Gas Sensor"] = gasSensorData;
  jsonDoc["Smoke Sensor"] = smokeSensorValue;

  // Add water level status to the JSON object
  if (waterLevel == 0) {
    jsonDoc["Water Level"] = "Empty";
  } else if (waterLevel > 0 && waterLevel <= lowerThreshold) {
    jsonDoc["Water Level"] = "Low";
    // if (millis() - lastAlertTime >= alertInterval) {
    //     testSMS("LVL1 WATER ALERT SMS");
    //     waterDetected = true;
    //     lastAlertTime = millis();
    //   }
  } else if (waterLevel > lowerThreshold && waterLevel <= upperThreshold) {
    jsonDoc["Water Level"] = "Medium";
      // if (millis() - lastAlertTime >= alertInterval) {
    //     testSMS("LVL2 WATER ALERT SMS");
    //     waterDetected = true;
    //     lastAlertTime = millis();
    //   }
  } else if (waterLevel > upperThreshold) {
    jsonDoc["Water Level"] = "High";
    // if (millis() - lastAlertTime >= alertInterval) {
    //     testSMS("LVL3 WATER ALERT SMS");
    //     waterDetected = true;
    //     lastAlertTime = millis();
    //   }
  }

  // Serialize the JSON object to a string
  String jsonString;
  serializeJson(jsonDoc, jsonString);

  // Print the JSON string to the Serial monitor
  Serial.println(jsonString);

  // You can send jsonString to the ESP8266 over Serial or through other communication methods here

	delay(10000); 
}

// FUNCTIONS

int readSensor() {
	digitalWrite(waterSensorPower, HIGH);
	delay(10);
	water_val = analogRead(waterSensorPin);
	digitalWrite(waterSensorPower, LOW);
	return water_val;
}

void sendSMS(String message) {

  gsmSerial.println("AT+CMGS=\"+639331877460\"\r"); // Replace with your recipient's phone number
  delay(1000);
  gsmSerial.println(message);
  delay(1000);
  gsmSerial.println((char)26); // End of message character
  delay(1000);
  gsmSerial.println();
  Serial.println("SMS sent!");
}

void testSMS(String msg) {
  Serial.println(msg);
}