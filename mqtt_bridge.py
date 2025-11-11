import json
import mysql.connector
import paho.mqtt.client as mqtt
from datetime import datetime

# === Konfigurasi Database ===
db_conf = {
    'host': '127.0.0.1',
    'user': 'root',
    'password': '',
    'database': 'iot_aerosphare',
    'autocommit': True
}

# === Konfigurasi MQTT ===
MQTT_BROKER = "broker.mqttdashboard.com"
TOPIC = "iot/sensor"

# === Callback ketika berhasil konek ke broker ===
def on_connect(client, userdata, flags, reason_code, properties=None):
    if reason_code == 0:
        print("✅ Terhubung ke broker MQTT")
        client.subscribe(TOPIC)
        print(f"📡 Subscribe ke topik: {TOPIC}")
    else:
        print("❌ Gagal konek, kode:", reason_code)

# === Callback ketika pesan diterima ===
def on_message(client, userdata, msg):
    try:
        payload = msg.payload.decode()
        data = json.loads(payload)
        suhu = float(data.get("suhu", 0))
        humidity = float(data.get("humidity", 0))
        lux = float(data.get("lux", 0))

        conn = mysql.connector.connect(**db_conf)
        cur = conn.cursor()
        sql = "INSERT INTO data_sensor (suhu, humidity, lux, timestamp) VALUES (%s, %s, %s, %s)"
        cur.execute(sql, (suhu, humidity, lux, datetime.now()))
        cur.close()
        conn.close()

        print(f"💾 Data disimpan: Suhu={suhu}°C, Kelembapan={humidity}%, Lux={lux}")
    except Exception as e:
        print("❌ Error:", e)

# === MQTT Client (versi baru kompatibel penuh) ===
client = mqtt.Client(client_id="bridge-subscriber", protocol=mqtt.MQTTv311)
client.on_connect = on_connect
client.on_message = on_message

# === Jalankan koneksi dan loop ===
print("🔄 Menghubungkan ke broker MQTT...")
client.connect(MQTT_BROKER, 1883, 60)
client.loop_forever()
