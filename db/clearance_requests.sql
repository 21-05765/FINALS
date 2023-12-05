--Database name : clearance_requests
CREATE TABLE clearance_requests (
    user_id INT(10),
    purpose VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    age INT(10) NOT NULL,
    address VARCHAR(255) NOT NULL,
    date DATE NOT NULL
);
