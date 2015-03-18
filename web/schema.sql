CREATE TABLE book (
	id SERIAL PRIMARY KEY,
	title VARCHAR(100),
	module VARCHAR(10),
	price INT,
	condition REAL,
	availability BIT,
	CHECK (condition < 10)
);

INSERT INTO book(title, module, price, condition, availability) VALUES ('Introduction to Algorithms', 'CS3230', 35, 8, '1');
INSERT INTO book(title, module, price, condition, availability) VALUES ('Interaction Design', 'CS3240', 55, 9.5, '1');
INSERT INTO book(title, module, price, condition, availability) VALUES ('Database Management', 'CS2102', 15, 6, '1');
INSERT INTO book(title, module, price, condition, availability) VALUES ('Database Management', 'CS2102', 20, 8.5, '0');
INSERT INTO book(title, module, price, condition, availability) VALUES ('Japanese II', 'LAJ2201', 25, 8, '1');
INSERT INTO book(title, module, price, condition, availability) VALUES ('Expensive Book', 'CS5241', 235, 5, '1');