--Database name : ub_clearance_requests
CREATE TABLE ub_clearance_requests (
    user_id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    bname VARCHAR(255) NOT NULL,
    btype VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    date DATE NOT NULL
);