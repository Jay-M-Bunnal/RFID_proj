-- Create database
CREATE DATABASE rfid_log;

-- Use database
USE rfid_log;

-- Create table
CREATE TABLE log (
  id INT AUTO_INCREMENT,
  timestamp DATETIME,
  tag_id VARCHAR(255),
  access_status VARCHAR(255),
  ultrasonic_sensor_reading VARCHAR(255),
  PRIMARY KEY (id)
);