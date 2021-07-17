
-- @block
CREATE TABLE Users(
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL UNIQUE,
    bio TEXT,
    country VARCHAR(2)
);

--@block
CREATE TABLE Rooms(
    id INT AUTO_INCREMENT,
    street VARCHAR(255),
    owner_id INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (owner_id) REFERENCES Users(id)
)

--@block
CREATE TABLE Bookings(
    id INT AUTO_INCREMENT,
    guest_id INT NOT NULL,
    room_id INT NOT NULL,
    check_in DATETIME,
    PRIMARY KEY (id),
    FOREIGN KEY (guest_id) REFERENCES Users(id),
    FOREIGN KEY (room_id) REFERENCES Rooms(id)
)

--@block
INSERT INTO Users (email, bio, country)
VALUES
    ('chung2@email.com', 'chung dep trai', 'VN'),
    ('hello@world.com', 'i like strangers', 'US'),
    ('bonjour@monde.com', 'baz', 'FR'),
    ('hola@munda.com', 'foo', 'MX')

--@block
INSERT INTO Rooms (owner_id, street)
VALUES
    (1, 'san diego sailboat'),
    (1, 'nantucket cottage'),
    (1, 'vail cabin'),
    (1, 'sf cardboard box');


--@block
INSERT INTO Bookings (guest_id, room_id, check_in)
VALUES
    (1, 2, NOW()),
    (1, 3, NOW()),
    (6, 3, NOW()),
    (6, 1, NOW())

--@block
CREATE INDEX email_index ON Users(email);

--@block
SELECT id, email FROM Users 
WHERE country = 'VN'
-- AND id > 1
AND email like 'chung%'
ORDER BY id ASC
LIMIT 2;

--@BLOCK
select * from Rooms;

--@block
SELECT
    Users.id as user_id,
    Rooms.id as room_id,
    email,
    street
FROM Users 
LEFT JOIN Rooms ON Rooms.owner_id = Users.id;

--@block Rooms as a user has booked
SELECT
    guest_id,
    room_id,
    street,
    check_in
FROM Bookings
INNER JOIN Rooms ON Rooms.id = room_id;

--@block drop TABLES
-- DROP TABLE Users;
-- DROP TABLE Rooms;
DROP TABLE Bookings;
