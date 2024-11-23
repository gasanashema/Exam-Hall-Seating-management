 CREATE TABLE admins (
  id int NOT NULL AUTO_INCREMENT,
  username varchar(255) NOT NULL,
  password varchar(255) NOT NULL,
  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY username (username)
);


 CREATE TABLE departments (
  id int NOT NULL AUTO_INCREMENT,
  department_name varchar(255) NOT NULL,
  department_description text,
  date_of_school timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY department_name (department_name)
);


CREATE TABLE exam_room (
  room_id int NOT NULL AUTO_INCREMENT,
  room_name varchar(255) NOT NULL,
  createdAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  updatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  status varchar(50) DEFAULT NULL,
  PRIMARY KEY (room_id)
);


CREATE TABLE seating_arrangements (
  id int NOT NULL AUTO_INCREMENT,
  department_id int NOT NULL,
  year varchar(10) NOT NULL,
  exam_name varchar(255) NOT NULL,
  sessions varchar(255) NOT NULL,
  exam_date date NOT NULL,
  start_time time NOT NULL,
  end_time time NOT NULL,
  teacher_id int NOT NULL,
  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY department_id (department_id),
  KEY teacher_id (teacher_id),
  CONSTRAINT seating_arrangements_ibfk_1 FOREIGN KEY (department_id) REFERENCES departments (id),
  CONSTRAINT seating_arrangements_ibfk_2 FOREIGN KEY (teacher_id) REFERENCES teachers (id)
)'
 

 CREATE TABLE seating_details (
  id int NOT NULL AUTO_INCREMENT,
  seating_arrangement_id int NOT NULL,
  room_id int NOT NULL,
  num_students int NOT NULL,
  PRIMARY KEY (id),
  KEY seating_arrangement_id (seating_arrangement_id),
  KEY room_id (room_id),
  CONSTRAINT seating_details_ibfk_1 FOREIGN KEY (seating_arrangement_id) REFERENCES seating_arrangements (id),
  CONSTRAINT seating_details_ibfk_2 FOREIGN KEY (room_id) REFERENCES exam_room (room_id)
)
 CREATE TABLE seats (
  id int NOT NULL AUTO_INCREMENT,
  student_id int DEFAULT NULL,
  room_id int DEFAULT NULL,
  set_number varchar(50) DEFAULT NULL,
  seating_arrangement_id int DEFAULT NULL,
  createdAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  updatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY student_id (student_id),
  KEY room_id (room_id),
  KEY seating_arrangement_id (seating_arrangement_id),
  CONSTRAINT seats_ibfk_1 FOREIGN KEY (student_id) REFERENCES students (id),
  CONSTRAINT seats_ibfk_2 FOREIGN KEY (room_id) REFERENCES exam_room (room_id),
  CONSTRAINT seats_ibfk_3 FOREIGN KEY (seating_arrangement_id) REFERENCES seating_arrangements (id)
);

 CREATE TABLE students (
  id int NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL,
  reg_no varchar(20) NOT NULL,
  department_id int NOT NULL,
  year varchar(3) NOT NULL,
  session varchar(100) DEFAULT 'day',
  PRIMARY KEY (id),
  UNIQUE KEY reg_no (reg_no)
);

CREATE TABLE teachers (
  id int NOT NULL AUTO_INCREMENT,
  username varchar(50) NOT NULL,
  email varchar(100) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY email (email)
);