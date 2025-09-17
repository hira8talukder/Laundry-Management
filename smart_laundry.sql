USE smart_laundry;

-- Users Registration Table
 -- Clear existing data if needed
CREATE TABLE sign_up (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE sign_up ADD COLUMN phone_number VARCHAR(20) AFTER email;

ALTER TABLE sign_up ADD COLUMN status ENUM('active', 'inactive', 'deleted') DEFAULT 'active';


CREATE TABLE user_profile (
    user_id INT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    phone_number VARCHAR(15) NOT NULL,
    date_of_birth DATE,
    address TEXT NOT NULL,       
    permanent_address TEXT,
    education VARCHAR(100),
    occupation VARCHAR(100),
    profile_picture VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES sign_up(user_id) ON DELETE CASCADE
);


-- Laundry Owner Table (if needed for admin management)
CREATE TABLE owner (
    owner_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone_number VARCHAR(15) NOT NULL,
    address TEXT,
    profile_picture VARCHAR(255)
);

-- Orders Table
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    phone_number VARCHAR(15) NOT NULL,
    product_type VARCHAR(100),
    product_quantity INT,
    number_of_products INT,
    pickup_type ENUM('Home Pickup', 'Store Drop') DEFAULT 'Home Pickup',
    delivery_type ENUM('Home Delivery', 'Store Pickup') NOT NULL,
    address TEXT NOT NULL,
    price DECIMAL(10,2),
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    max_delivery_date DATE,
    FOREIGN KEY (user_id) REFERENCES sign_up(user_id) ON DELETE CASCADE
);


ALTER TABLE orders ADD status ENUM('Pending', 'In Progress', 'Completed', 'Cancelled') DEFAULT 'Pending';


-- Billing Table
CREATE TABLE bill (
    order_id INT PRIMARY KEY,
    user_id INT NOT NULL,
    product_type VARCHAR(100) NOT NULL,
    product_quantity INT NOT NULL,
    billing_amount DECIMAL(10,2) NOT NULL,
    payment_status ENUM('Paid', 'Unpaid', 'Pending') DEFAULT 'Pending',
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES sign_up(user_id)
);

-- Service Progress Table
CREATE TABLE service_progress (
    order_id INT PRIMARY KEY,
    service_status ENUM('Received', 'Washing', 'Ironing', 'Completed', 'Delivered') DEFAULT 'Received',
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE
);



SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smart_laundry`
--

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `name` VARCHAR(100) DEFAULT NULL,
  `service` VARCHAR(100) DEFAULT NULL,
  `rating` DECIMAL(2,1) DEFAULT NULL,
  `review_text` TEXT DEFAULT NULL,
  `review_date` DATE DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `sign_up` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `name`, `service`, `rating`, `review_text`, `review_date`) VALUES
(1, 1, 'Test User', 'Dry Wash', 4.5, 'Great service!', '2025-06-27'),
(2, 21, 'Nusrat', 'Dry Wash', 4.0, 'Well done !', '2025-06-27'),
(3, 21, 'Nusrat', 'Dry Wash', 4.0, 'Well done !', '2025-06-27'),
(4, 21, 'Nusrat', 'Dry Wash', 5.0, 'Good !', '2025-06-27'),
(5, 23, 'Wali', 'Wash', 3.5, 'WELL SERVICE!', '2025-06-27');


INSERT INTO reviews (user_id, name, service, rating, review_text, review_date)
VALUES (1, 'Test User', 'Dry Wash', 4.5, 'Great service!', '2025-06-27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `sign_up` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
