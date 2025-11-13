#include <WiFi.h>
#include <HTTPClient.h>
#include <WiFiClientSecure.h>
#include "DHT.h"
#include <OneWire.h>
#include <DallasTemperature.h>
#include <ArduinoJson.h> //

// ======================== CONFIGURACIÓN DE PINES ========================
#define DHTPIN 15
#define DHTTYPE DHT22
#define MQ135_PIN 34
#define FAN_PIN 12
#define PUMP_PIN 14
#define SOIL_MOISTURE_PIN 35
#define ONE_WIRE_BUS 4
#define ON_BOARD_LED 2

// ======================== INSTANCIAS DE SENSORES ========================
DHT dhtSensor(DHTPIN, DHTTYPE);
OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature ds18b20(&oneWire);

// ======================== CONFIGURACIÓN WiFi ============================
const char* ssid = "nombre_wifi";
const char* password = "contraseña";

// ======================== VARIABLES GLOBALES ============================
int idUser = 2;
String api_token = "gH4kL9pRjQ7sW2mX";
int tempMin, tempMax;
int humMin, humMax;
int soilMin, soilMax;
int gasMax;
String fase;
int typeAlert = 1; //

float send_Temp;
int send_Humd;
int mq135_value;
String send_Status_Read_DHT11 = "";
String air_quality_status = "";
float ds18b20_temp = 0.0;
int soil_moisture_value = 0;

// Gases estimados
float ammonia, co2, co, benzene, alcohol, smoke;

// Temporizador para sensores (5 segundos)
unsigned long previousMillis = 0;
const long interval = 5000;

// ============================= FUNCIONES =============================

// --- Lectura del sensor DHT22 ---
bool get_DHT22_sensor_data() {
  send_Temp = dhtSensor.readTemperature();
  send_Humd = dhtSensor.readHumidity();

  if (isnan(send_Temp) || isnan(send_Humd)) {
    send_Status_Read_DHT11 = "FAILED";
    Serial.println("❌ Error: Falló lectura del DHT22");
    return false;
  } else {
    send_Status_Read_DHT11 = "SUCCEED";
    Serial.printf("🌡️ DHT22 - Temp: %.2f °C | Hum: %d %%\n", send_Temp, send_Humd);
    return true;
  }
}

// --- Lectura del sensor MQ135 ---
bool get_MQ135_sensor_data() {
  mq135_value = analogRead(MQ135_PIN);
  if (mq135_value <= 0) {
    Serial.println("❌ Error: MQ135 devolvió un valor inválido.");
    return false;
  }

  float total = mq135_value;
  ammonia = total * 0.20;
  co2 = total * 0.25;
  co = total * 0.15;
  benzene = total * 0.10;
  alcohol = total * 0.20;
  smoke = total * 0.10;

  if (mq135_value < 200) air_quality_status = "Calidad óptima";
  else if (mq135_value <= 450) air_quality_status = "Actividad microbiana";
  else if (mq135_value <= 650) air_quality_status = "Acumulación de gases";
  else air_quality_status = "Gases nocivos";

  Serial.printf("🧪 MQ135: %d | Aire: %s\n", mq135_value, air_quality_status.c_str());
  return true;
}

// --- Lectura del sensor DS18B20 ---
bool get_DS18B20_data() {
  ds18b20.requestTemperatures();
  ds18b20_temp = ds18b20.getTempCByIndex(0);
  if (ds18b20_temp == DEVICE_DISCONNECTED_C) {
    Serial.println("❌ Error: DS18B20 desconectado o fallo de lectura.");
    return false;
  }
  Serial.printf("🌱 Temp suelo DS18B20: %.2f °C\n", ds18b20_temp);
  return true;
}

// --- Lectura del sensor de humedad del suelo ---
bool get_soil_moisture_data() {
  int raw = analogRead(SOIL_MOISTURE_PIN);
  if (raw <= 0) {
    Serial.println("❌ Error: Lectura de humedad del suelo inválida.");
    return false;
  } else {
    Serial.printf("📟 Valor crudo del sensor: %d\n", raw);
  }

  int seco = 2456;
  int humedo = 2143;
  raw = constrain(raw, humedo, seco);
  soil_moisture_value = map(raw, seco, humedo, 0, 100);
  Serial.printf("💧 Humedad Suelo: %d %% (raw: %d)\n", soil_moisture_value, raw);
  return true;
}

void set_Control_Thresholds() {
  
    switch (typeAlert) {
        case 1: // Etapa inicial
            tempMin = 20; tempMax = 40;
            humMin = 40; humMax = 80;
            soilMin = 35; soilMax = 70;
            gasMax = 500;
            fase = "Inicial";
            break;

        case 2: // Etapa media (alta actividad microbiana)
            tempMin = 45; tempMax = 65;
            humMin = 40; humMax = 70;
            soilMin = 30; soilMax = 60;
            gasMax = 800;
            fase = "Media";
            break;

        case 3: // Etapa final (maduración)
            tempMin = 25; tempMax = 35;
            humMin = 30; humMax = 60;
            soilMin = 25; soilMax = 50;
            gasMax = 500;
            fase = "Final";
            break;
            
        default: // Si typeAlert es un valor inesperado, usa la Fase 1
            tempMin = 20; tempMax = 40;
            humMin = 40; humMax = 80;
            soilMin = 35; soilMax = 70;
            gasMax = 500;
            fase = "Inicial (Default)";
            break;
    }
    
    Serial.println("ℹ️ Umbrales actualizados a Fase: " + fase);
}
// --- Control del ventilador ---
void control_fan() {
  bool activar = send_Temp > tempMax || send_Humd > humMax || mq135_value > gasMax;
  digitalWrite(FAN_PIN, activar ? LOW : HIGH);  // Active LOW
  Serial.println(activar ? "🌀 VENTILADOR ON" : "🌀 VENTILADOR OFF");
}

// --- Control de la bomba de agua ---
void control_pump() {
  bool activar = (soil_moisture_value < soilMin);
  digitalWrite(PUMP_PIN, activar ? LOW : HIGH);  // Active LOW
  Serial.println(activar ? "💦 BOMBA DE AGUA ON" : "💦 BOMBA DE AGUA OFF");
}

// ============================= CONEXIÓN WiFi =============================
bool connectWiFi() {
  Serial.printf("🔌 Conectando a WiFi: %s\n", ssid);
  WiFi.begin(ssid, password);
  int timeout = 40;

  while (WiFi.status() != WL_CONNECTED && timeout-- > 0) {
    delay(250);
    digitalWrite(ON_BOARD_LED, !digitalRead(ON_BOARD_LED));
  }

  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("✅ WiFi conectado correctamente");
    digitalWrite(ON_BOARD_LED, HIGH);
    return true;
  } else {
    Serial.println("❌ Error: No se pudo conectar a WiFi");
    digitalWrite(ON_BOARD_LED, LOW);
    return false;
  }
}


// ======================= NUEVA FUNCIÓN: OBTENER TIPO DE ALERTA =======================
void get_Alert_Type() {
    Serial.println("📡 Pidiendo tipo de alerta...");

    WiFiClientSecure client;
    client.setInsecure(); // ¡ADVERTENCIA: Inseguro! Considera usar certificados raíz.
    HTTPClient https;

    // Construir la URL con parámetros GET
    String url = "https://compos.alwaysdata.net/api/get_Type_Alert";
    url += "?idUser=" + String(idUser);
    url += "&api_token=" + api_token;

    if (https.begin(client, url)) {
        int httpCode = https.GET();

        if (httpCode == HTTP_CODE_OK) { // 200
            String payload = https.getString();
            Serial.println("📩 Respuesta de Alerta: " + payload);

            // Parsear el JSON
            DynamicJsonDocument doc(256); // Aumenta si el JSON es más grande
            DeserializationError error = deserializeJson(doc, payload);

            if (error) {
                Serial.print("❌ Error al parsear JSON de alerta: ");
                Serial.println(error.c_str());
            } else {
                typeAlert = doc["type_alert"].as<int>(); // <--- CAMBIA A .as<int>()
                Serial.println("✅ Tipo de Alerta actualizado: " + String(typeAlert)); // Lo convertimos a String solo para imprimirlo
            }

        } else if (httpCode == 401) {
            Serial.println("❌ Error 401: No autorizado. Revisa tu api_token.");
        } else {
            Serial.printf("❌ Error HTTP [GET]: %s\n", https.errorToString(httpCode).c_str());
        }

        https.end();
    } else {
        Serial.println("❌ No se pudo conectar a la URL de alerta.");
    }
}

// ======================= NUEVA FUNCIÓN: ENVIAR DATOS DE SENSORES (POST) =======================
void send_Sensor_Data() {
    WiFiClientSecure client;
    client.setInsecure();
    HTTPClient https;

    String payload = "{";
    payload += "\"idUser\":" + String(idUser);
    payload += ",\"api_token\":\"" + api_token + "\"";
    payload += ",\"temperature\":" + String(send_Temp, 2);
    payload += ",\"humidity\":" + String(send_Humd);
    payload += ",\"status\":\"" + send_Status_Read_DHT11 + "\"";
    payload += ",\"mq135\":" + String(mq135_value);
    payload += ",\"air_quality_status\":\"" + air_quality_status + "\"";
    payload += ",\"ammonia\":" + String(ammonia, 2);
    payload += ",\"co2\":" + String(co2, 2);
    payload += ",\"co\":" + String(co, 2);
    payload += ",\"benzene\":" + String(benzene, 2);
    payload += ",\"alcohol\":" + String(alcohol, 2);
    payload += ",\"smoke\":" + String(smoke, 2);
    payload += ",\"ds18b20_temp\":" + String(ds18b20_temp, 2);
    payload += ",\"soil_moisture\":" + String(soil_moisture_value);
    payload += "}";

    Serial.println("📤 Enviando datos de sensores al servidor...");

    https.begin(client, "https://compos.alwaysdata.net/api/dashboard/store");
    https.addHeader("Content-Type", "application/json");

    int httpCode = https.POST(payload);

    if (httpCode > 0) {
        Serial.printf("✅ HTTP Code [POST]: %d\n", httpCode);
        String response = https.getString();
        Serial.println("📩 Respuesta [POST]: " + response);
    } else {
        Serial.printf("❌ Error HTTP [POST]: %s\n", https.errorToString(httpCode).c_str());
    }

    https.end();
}

// ============================= SETUP =============================
void setup() {
    Serial.begin(115200);
    pinMode(ON_BOARD_LED, OUTPUT);
    pinMode(MQ135_PIN, INPUT);
    pinMode(FAN_PIN, OUTPUT);
    pinMode(PUMP_PIN, OUTPUT);
    pinMode(SOIL_MOISTURE_PIN, INPUT);

    digitalWrite(FAN_PIN, HIGH);
    digitalWrite(PUMP_PIN, HIGH);

    digitalWrite(ON_BOARD_LED, HIGH);
    delay(1000);
    digitalWrite(ON_BOARD_LED, LOW);

    if (!connectWiFi()) ESP.restart();

    dhtSensor.begin();
    ds18b20.begin();
    Serial.println("✅ Sensores inicializados correctamente");
    
    // Opcional: Obtener el tipo de alerta al iniciar
    get_Alert_Type(); 
    set_Control_Thresholds();
}

// ============================= LOOP PRINCIPAL (MODIFICADO) =============================
void loop() {
    
    // --- 1. Manejador de Conexión WiFi ---
    if (WiFi.status() != WL_CONNECTED) {
        Serial.println("📶 WiFi desconectado, intentando reconectar...");
        if(connectWiFi()) {
           // Si se reconecta, pide la alerta y umbrales
           get_Alert_Type();
           set_Control_Thresholds(); 
        }
        return; // Esperar a la siguiente iteración
    }

    unsigned long currentMillis = millis();

    // --- 2. TEMPORIZADOR ÚNICO (Cada 5 segundos) ---
    if (currentMillis - previousMillis >= interval) {
        previousMillis = currentMillis; // Reiniciar el temporizador

        Serial.println("\n--- Tarea de 5 seg: Inicia Ciclo Completo ---");
        
        // --- 2.1. Obtener Fase Actual ---
        Serial.println("📡 Obteniendo tipo de alerta...");
        get_Alert_Type();

        // --- 2.2. Ajustar Límites ---
        Serial.println("ℹ️ Ajustando umbrales...");
        set_Control_Thresholds();
        
        // --- 2.3. Leer Sensores ---
        Serial.println("🔬 Leyendo sensores...");
        get_DHT22_sensor_data();
        get_MQ135_sensor_data();
        get_DS18B20_data();
        get_soil_moisture_data();

        // --- 2.4. Controlar Actuadores ---
        Serial.println("⚙️ Controlando actuadores...");
        control_fan();
        control_pump();

        // --- 2.5. Enviar Datos al Servidor ---
        send_Sensor_Data();
    }
}
