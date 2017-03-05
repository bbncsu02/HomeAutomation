#include <ESP8266WiFi.h>
#include <DHT.h>
//#define DHTTYPE DHT11
#define DHTTYPE DHT22
#define DHTPIN  2
//#define LEDPIN 0

DHT dht(DHTPIN, DHTTYPE, 11); // 11 works fine for ESP8266

String uuid = "";
const char* ssid     = "Buchanan";
const char* password = "bbncsupack02";

const char* _host = "192.168.2.50";
const int _httpPort = 80;
const char* _url = "/SensorData/PostData.php";

float humidity, temp;  // Values read from sensor
// Generally, you should use "unsigned long" for variables that hold time
unsigned long previousMillis = 0;        // will store last temp was read
const long minRefreshInterval = 5000;

int m_delay = 5000;
bool _printSerial = true;

void setup() {
  //pinMode(LEDPIN, OUTPUT);
  Serial.begin(115200);
  delay(100);

  setUuid();
  getConfig();

  // We start by connecting to a WiFi network
  connectWifi();
}

void loop() {
  delay(m_delay);

  //digitalWrite(LEDPIN, HIGH);
  if(_printSerial) Serial.print("connecting to ");
  if(_printSerial) Serial.println(_host);

  // Use WiFiClient class to create TCP connections
  WiFiClient client;
  if (!client.connect(_host, _httpPort)) {
    if(_printSerial) Serial.println("connection failed");
    return;
  }

  getTemperatureHumidity();
  
  // We now create a URI for the request
  String parameters = "?uuid=" + uuid + "&temp=" + temp + "&humidity=" + humidity;
  
  String fullUrl= _url + parameters;
  
  if(_printSerial) Serial.print("Requesting URL: ");
  if(_printSerial) Serial.println(fullUrl);

  // This will send the request to the server
  client.print(String("GET ") + fullUrl + " HTTP/1.1\r\n" +
               "Host: " + _host + "\r\n" +
               "Connection: close\r\n\r\n");
  delay(500);
  int lineNum = -1;
  String configLine = "";
  // Read all the lines of the reply from server and print them to Serial
  while (client.available()) {
    lineNum++;
    String line = client.readStringUntil('\r');
    if(lineNum == 8) configLine = line;
    if(_printSerial) Serial.print(line);
  }
  
  bool printSerial = configLine.substring(1, 2) == "1";
  String sleeptime = configLine.substring(3);
  m_delay = sleeptime.toInt();
  if(_printSerial) Serial.println("\nSerial Output: " + String(printSerial) + " Sleep Time: " + sleeptime);
  if(_printSerial) Serial.println("\nclosing connection\n\n");
  //digitalWrite(LEDPIN, LOW);
  _printSerial = printSerial;
}

void getTemperatureHumidity() {
  // Wait at least interval seconds between measurements.
  // if the difference between the current time and last time you read
  // the sensor is bigger than the interval you set, read the sensor
  // Works better than delay for things happening elsewhere also
  unsigned long currentMillis = millis();

  if (currentMillis - previousMillis >= minRefreshInterval) {
    // save the last time you read the sensor
    previousMillis = currentMillis;

    // Reading temperature for humidity takes about 250 milliseconds!
    // Sensor readings may also be up to 2 seconds 'old' (it's a very slow sensor)
    humidity = dht.readHumidity();          // Read humidity (percent)
    temp = dht.readTemperature(true);     // Read temperature as Fahrenheit
    // Check if any reads failed and exit early (to try again).
    if (isnan(humidity) || isnan(temp)) {
      if(_printSerial) Serial.println("Failed to read from DHT sensor!");
      return;
    }
  }
}

// Get the configuration elements from the server
void getConfig()
{
// todo implement configuration url request to server at boot
}

void setUuid()
{
  String
  mac = WiFi.macAddress();
  mac.replace(":", "");
  String espid = String(ESP.getChipId());
  uuid = mac + espid;
}

void connectWifi()
{
  if(_printSerial) Serial.print("\n\nConnecting to ");
  if(_printSerial) Serial.println(ssid);

  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    if(_printSerial) Serial.print(".");
  }

  if(_printSerial) Serial.println("\nWiFi connected");
  if(_printSerial) Serial.print("IP address: ");
  if(_printSerial) Serial.println(WiFi.localIP());
}

