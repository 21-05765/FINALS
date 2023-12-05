--Database name : income_requests
CREATE TABLE income_requests (
    user_id INT(10),
    name VARCHAR(255) NOT NULL,
    age INT(10) NOT NULL,
    civil_status VARCHAR(50) NOT NULL,
    address VARCHAR(255) NOT NULL,
    income INT(10) NOT NULL,
    date DATE NOT NULL
);