import serial
import requests
from datetime import datetime
import os

# Excel file setup
filename = "rfid_log.xlsx"

# Serial connection setup
ser = serial.Serial('COM3', 9600, timeout=1)  # Replace COM3 with your Arduino serial port

# Master tag setup
master_tag = "52694B55"  # Set master tag
master_tag_set = False

# Log data function
def log_data(timestamp, tag_id, access_status, ultrasonic_sensor_reading):
    url = "http://localhost/Web/log.php"
    data = {
        "timestamp": timestamp,
        "tag_id": tag_id,
        "access_status": access_status,
        "ultrasonic_sensor_reading": ultrasonic_sensor_reading
    }
    try:
        response = requests.post(url, data=data)
        response.raise_for_status()  # Raise an exception for bad status codes
    except requests.exceptions.RequestException as e:
        print(f"Error logging data: {e}")

try:
    while True:
        # Read serial data from Arduino
        data = ser.readline().decode().strip()
        
        if data:
            # Parse data
            if data.startswith('MASTER TAG SET:'):
                tag_id = data.split(': ')[1]
                if tag_id == master_tag:
                    master_tag_set = True
                    print(f"Master Tag Set: {tag_id}")
                    log_data(datetime.now().strftime("%Y-%m-%d %H:%M:%S"), tag_id, "", "")
                else:
                    print(f"Invalid Master Tag: {tag_id}")
                    
            elif data.startswith('ACCESS GRANTED:') and master_tag_set:
                tag_id = data.split(': ')[1]
                print(f"Access Granted: {tag_id}")
                log_data(datetime.now().strftime("%Y-%m-%d %H:%M:%S"), tag_id, "GRANTED", "")
                
            elif data.startswith('ACCESS DENIED:') and master_tag_set:
                tag_id = data.split(': ')[1]
                print(f"Access Denied: {tag_id}")
                log_data(datetime.now().strftime("%Y-%m-%d %H:%M:%S"), tag_id, "DENIED", "")
                
            elif data.startswith('DOOR OPENED') and master_tag_set:
                print("Door Opened!")
                log_data(datetime.now().strftime("%Y-%m-%d %H:%M:%S"), "", "", "DOOR OPEN")
                
            elif data.startswith('ultrasonic:') and master_tag_set:
                ultrasonic_reading = data.split(': ')[1]
                print(f"Ultrasonic Sensor Reading: {ultrasonic_reading}")
                log_data(datetime.now().strftime("%Y-%m-%d %H:%M:%S"), "", "", ultrasonic_reading)
                
except KeyboardInterrupt:
    print("Exiting program...")
    
except Exception as e:
    print(f"Error: {e}")