--Database name : residency_requests
CREATE TABLE residency_requests (
    user_id INT(10),
    purpose VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    age INT(10) NOT NULL,
    civil_status VARCHAR(50) NOT NULL,
    citizenship VARCHAR(255) NOT NULL,
    gender VARCHAR(10) NOT NULL,
    address VARCHAR(255) NOT NULL,
    date DATE NOT NULL
);