-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 23, 2025 at 10:17 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookmyroom`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `password`) VALUES
(25, 'raj', '$2y$10$k2AR3rCvSoodITUn8I5.quTsqMsBCiQE.gqH3nCuyWA4/BXcTt2fS'),
(28, 'savan', '$2y$10$p3VvLUa84SFSHqf9xSLiwu7NYlqXD/ZQJ4DG.HWBa2Z3NA2bYtpcC'),
(33, 'dev', '$2y$10$19cNkEhvS6sYbwoioa5sU.yYTYiqy9./OiI2VrGrzDdAiEdd6WCmW');

-- --------------------------------------------------------

--
-- Table structure for table `cab_booking_hotel`
--

CREATE TABLE `cab_booking_hotel` (
  `id` int(11) NOT NULL,
  `hotel_booking_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `pickup` varchar(255) NOT NULL,
  `dropoff` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `contact` varchar(15) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'booked'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cab_booking_hotel`
--

INSERT INTO `cab_booking_hotel` (`id`, `hotel_booking_id`, `username`, `pickup`, `dropoff`, `date`, `time`, `contact`, `created_at`, `status`) VALUES
(24, 80, 'Rajj_1632', 'unnamed road, Rajkot, Rajkot - 360001, Gujarat, India', 'Taj Hotel', '2025-02-25', '11:00:00', '8000247299', '2025-02-22 05:09:42', 'booked'),
(25, 89, 'Rajj_1632', 'unnamed road, Rajkot, - 360006, Gujarat, India', 'Ocean View', '2025-02-27', '11:00:00', '8238388031', '2025-02-25 16:05:37', 'booked'),
(26, 90, 'Rajj_1632', 'unnamed road, Gandhinagar, Gandhinagar - 382028, Gujarat, India', 'Taj Hotel', '2025-03-01', '11:00:00', '8238388031', '2025-02-28 10:06:53', 'booked'),
(28, 93, 'Rajj_1632', 'unnamed road, Ghuma, - 380058, Gujarat, India', 'Taj Hotel', '2025-03-15', '11:00:00', '9879021452', '2025-03-08 16:05:20', 'booked'),
(29, 94, 'Rajj_1632', 'unnamed road, Naranpura, - 380013, Gujarat, India', 'Regenta Hotel', '2025-03-26', '11:00:00', '8238388031', '2025-03-23 07:23:35', 'booked');

-- --------------------------------------------------------

--
-- Table structure for table `cab_booking_resort`
--

CREATE TABLE `cab_booking_resort` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `resort_booking_id` int(11) NOT NULL,
  `pickup` varchar(255) NOT NULL,
  `dropoff` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `contact` varchar(15) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'booked'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cab_booking_resort`
--

INSERT INTO `cab_booking_resort` (`id`, `username`, `resort_booking_id`, `pickup`, `dropoff`, `date`, `time`, `contact`, `created_at`, `status`) VALUES
(10, 'Rajj_1632', 35, 'unnamed road, Rajkot, Rajkot - 360001, Gujarat, India', 'Dallas Resort', '2025-03-04', '11:00:00', '8000247299', '2025-02-22 05:11:25', 'booked'),
(12, 'Rajj_1632', 39, 'unnamed road, Naranpura, - 380013, Gujarat, India', 'Grand Leela', '2025-03-28', '11:00:00', '8238388031', '2025-03-23 07:32:03', 'booked'),
(13, 'Rajj_1632', 38, 'unnamed road, Naranpura, - 380013, Gujarat, India', 'Rajasthali', '2025-04-08', '11:00:00', '8238388031', '2025-03-23 07:32:37', 'booked');

-- --------------------------------------------------------

--
-- Table structure for table `carousel_images`
--

CREATE TABLE `carousel_images` (
  `id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carousel_images`
--

INSERT INTO `carousel_images` (`id`, `image_url`) VALUES
(1, 'assets/images/hero6.jpg'),
(2, 'assets/images/hero2.png'),
(3, 'assets/images/hero3.jpg'),
(4, 'assets/images/hero5.jpg'),
(5, 'assets/images/hero1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `hotels1`
--

CREATE TABLE `hotels1` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `features` text NOT NULL,
  `facilities` text NOT NULL,
  `guest_capacity` varchar(50) NOT NULL,
  `area` varchar(50) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `iframe` varchar(2000) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotels1`
--

INSERT INTO `hotels1` (`id`, `name`, `location`, `price`, `description`, `features`, `facilities`, `guest_capacity`, `area`, `image_url`, `iframe`, `last_updated`) VALUES
(1, 'Taj Hotel', 'Mumbai', 2600.00, 'Experience Timeless Luxury at Taj Hotel, Mumbai\r\n\r\nNestled in the heart of Mumbai, Taj Hotel is a symbol of grandeur and Indian heritage, combining world-class luxury with timeless elegance. Established in 1903, this iconic hotel stands proudly near the Gateway of India, offering breathtaking views of the Arabian Sea.', 'bedroom, balcony, sofa', 'AC, TV, Heater, Geyser', '5 Adults, 3 Children', '250 sq. ft.', 'assets\\images\\tajhotel.jpg', 'https://www.google.com/maps/embed?pb=!4v1741502438379!6m8!1m7!1s7adMrsua8eTzUrzySsNDZA!2m2!1d18.92215485777312!2d72.8336445908865!3f233.04270509036346!4f30.112427150446464!5f0.7820865974627469', '2025-03-23 08:58:00'),
(3, 'The Oberoi', 'New Delhi', 2500.00, 'The Oberoi, New Delhi is a luxury five-star hotel offering elegant rooms with stunning views, exceptional dining options like Indian at Omya and Chinese at Baoshuan, and world-class amenities. It combines contemporary design with warm Indian hospitality, situated near iconic landmarks like Humayun\'s Tomb.', 'bedroom, balcony, sofa', 'AC, TV, Heater, Geyser', '5 Adults, 3 Children', '250 sq. ft.', 'assets\\images\\oberoi.jpg', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d112121.79289490523!2d77.0854071931747!3d28.575586867520382!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390ce2e1f627bc0d%3A0x40c25df4a92d1404!2sThe%20Oberoi%2C%20New%20Delhi!5e0!3m2!1sen!2sin!4v1733830582697!5m2!1sen!2sin', '2025-02-26 12:12:54'),
(4, 'Regenta Hotel', 'Ahmedabad', 2500.00, 'Regenta Central Antarim, Ahmedabad is a modern boutique hotel offering stylish rooms, premium amenities, and exceptional hospitality. Conveniently located near key business hubs and cultural attractions, it features a multi-cuisine restaurant, a fitness center, and contemporary meeting spaces, ideal for both business and leisure travelers.', 'bedroom, balcony, sofa', 'AC, TV, Heater, Geyser', '5 Adults, 3 Children', '250 sq. ft.', 'assets\\images\\regenta.jpg', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d117495.0285894355!2d72.50889260633826!3d23.034063895856526!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e84f212258c29%3A0xec003b89646623fd!2sRegenta%20Central%20Antarim%20Hotel!5e0!3m2!1sen!2sin!4v1733831101400!5m2!1sen!2sin', '2025-02-26 12:12:54'),
(5, 'Ocean View', 'Goa', 3000.00, 'Ocean View - The Apartment Hotel, Goa offers spacious, fully-furnished apartments with modern amenities and stunning views of the ocean. Located near popular beaches, it provides a perfect blend of comfort and convenience for leisure travelers, featuring a pool, on-site dining, and easy access to Goa\'s vibrant attractions.', 'bedroom, balcony, sofa', 'AC, TV, Heater, Geyser', '5 Adults, 3 Children', '250 sq. ft.', 'assets\\images\\ocean.jpg', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d961.7676312711521!2d73.88900488100752!3d15.372666393233596!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bbfb82ac26be705%3A0xb41538a268406226!2sOcean%20View%20The%20Apartment%20Hotel!5e0!3m2!1sen!2sin!4v1733831708099!5m2!1sen!2sin', '2025-02-26 12:12:54');

-- --------------------------------------------------------

--
-- Table structure for table `hotel_booking`
--

CREATE TABLE `hotel_booking` (
  `booking_id` int(11) NOT NULL,
  `room_id` varchar(255) NOT NULL,
  `rname` varchar(255) NOT NULL,
  `hname` varchar(50) NOT NULL,
  `hprice` varchar(10) NOT NULL,
  `checkin` date NOT NULL,
  `checkout` date NOT NULL,
  `adult` int(10) NOT NULL,
  `child` int(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `userphno` varchar(50) NOT NULL,
  `useremail` varchar(50) NOT NULL,
  `payment_id` varchar(255) NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotel_booking`
--

INSERT INTO `hotel_booking` (`booking_id`, `room_id`, `rname`, `hname`, `hprice`, `checkin`, `checkout`, `adult`, `child`, `username`, `fullname`, `userphno`, `useremail`, `payment_id`, `order_id`, `status`) VALUES
(80, '1', 'Deluxe Room', 'Taj Hotel', '5000', '2025-02-28', '2025-03-02', 2, 1, 'Rajj_1632', 'Raj Patel', '8000247299', 'raj2584561@gmail.com', 'pay_PydSWOy81sq9v8', 'order_PydSCyA5ZP1Gks', 'paid'),
(89, '11', 'Standard Room', 'Ocean View', '6000', '2025-02-27', '2025-03-01', 2, 1, 'Rajj_1632', 'Raj Patel', '8238388031', 'raj2584561@gmail.com', 'pay_Q00EaMaobpoYuR', 'order_Q00ENbEAYuUv52', 'paid'),
(90, '2', 'Suite', 'Taj Hotel', '6425.66', '2025-03-01', '2025-03-03', 2, 1, 'Rajj_1632', 'Raj Patel', '8238388031', 'raj2584561@gmail.com', 'pay_Q15iPeteEi9oWg', 'order_Q15iExYKoWkYCb', 'paid'),
(91, '2', 'Suite', 'Taj Hotel', '6310.1', '2025-03-11', '2025-03-13', 2, 1, 'Rajj_1632', 'Raj Patel', '8238388031', 'raj2584561@gmail.com', 'pay_Q1PyxZhHLNYzOD', 'order_Q1PyhuAhkkJngr', 'paid'),
(93, '1', 'Deluxe Room', 'Taj Hotel', '6381.9', '2025-03-15', '2025-03-17', 2, 0, 'Rajj_1632', 'Raj Patel', '9879021452', 'raj643995@gmail.com', 'pay_Q4M6sUjKZ71KR1', 'order_Q4M6cgjQjVas3x', 'paid'),
(94, '12', 'Family Suite', 'Regenta Hotel', '3505.33', '2025-03-26', '2025-03-27', 2, 1, 'Rajj_1632', 'Raj Patel', '8238388031', 'raj2584561@gmail.com', 'pay_QA8ae3HCPa75cj', 'order_QA8aStu36TM1Do', 'paid'),
(97, '10', 'Standard Room', 'Regenta Hotel', '3060.94', '2025-03-27', '2025-03-28', 2, 1, 'Rajj_1632', 'Raj Patel', '8238388031', 'raj2584561@gmail.com', 'pay_QA9B3eORz8AnYS', 'order_QA9AN2tuQszhjY', 'paid');

-- --------------------------------------------------------

--
-- Table structure for table `hotel_managers`
--

CREATE TABLE `hotel_managers` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(30) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotel_managers`
--

INSERT INTO `hotel_managers` (`id`, `property_id`, `name`, `email`, `password`) VALUES
(7, 1, 'Raj', 'opever267@gmail.com', '$2y$10$DQwHUzzGYEWEZswjGib3s.0bZuVmc5kbNi1tFDx9oTNhMAfL7xICa'),
(8, 3, 'raj', 'raj2584561@gmail.com', '$2y$10$5KGEpnVAPInER2qYIRKnKuCH/Lp7vC.4UrZxRaeOaWQQrRR4394fC'),
(9, 4, 'savan', 'savankaneriya03@gmail.com', '$2y$10$u4GrUdm7lQVMvA6nPlYM3uFxnVteTRpVLVOXJf7SkQCZkvHt2HkJW'),
(10, 5, 'dev', 'devankani0@gamil.com', '$2y$10$KOvJvehC50cSenw9PAbP7OiHbXaOjm/oS3YFzD4RkQTFQNvhCO5CC');

-- --------------------------------------------------------

--
-- Table structure for table `hotel_reviews`
--

CREATE TABLE `hotel_reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `property_id` int(11) DEFAULT NULL,
  `rating` float DEFAULT NULL CHECK (`rating` between 1 and 5),
  `review` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotel_reviews`
--

INSERT INTO `hotel_reviews` (`id`, `user_id`, `property_id`, `rating`, `review`, `created_at`) VALUES
(1, 41, 1, 5, 'Great Experience ', '2025-03-07 02:54:52'),
(8, 41, 5, 4, 'Nice', '2025-03-20 12:35:12');

-- --------------------------------------------------------

--
-- Table structure for table `nearby_hotel`
--

CREATE TABLE `nearby_hotel` (
  `place_id` int(20) NOT NULL,
  `hotel_id` int(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `location` text NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nearby_hotel`
--

INSERT INTO `nearby_hotel` (`place_id`, `hotel_id`, `name`, `description`, `location`, `image_url`) VALUES
(4, 1, 'Chhatrapati Shivaji Maharaj Terminus', 'Chhatrapati Shivaji Terminus (officially Chhatrapati Shivaji Maharaj Terminus since 2017, formerly Victoria Terminus (VT), Bombay station code: CSMT (mainline)[6]/ST (suburban)), is a historic railway terminus and UNESCO World Heritage Site in Mumbai, Maharashtra, India.', 'New Mumbai', 'assets/images/csmt.jpg'),
(5, 1, 'Gateway Of India', 'The Gateway of India is an arch-monument completed in 1924 on the waterfront of Mumbai, India. It was erected to commemorate the landing of George V for his coronation as the Emperor of India in December 1911 at Strand Road near Wellington Fountain. He was the first British monarch to visit India.', 'New Mumbai', 'assets/images/gate.jpg'),
(6, 1, 'Elephanta Crave', '\r\nThe Elephanta Caves, located on Elephanta Island near Mumbai, India, are a UNESCO World Heritage Site renowned for their stunning rock-cut architecture and intricate sculptures. Dating back to the 5th–7th centuries, these caves primarily feature Hindu deities, with the iconic 20-foot Trimurti sculpture of Lord Shiva as the centerpiece. The caves reflect India’s rich cultural and artistic heritage and attract visitors worldwide for their historical and spiritual significance.', 'New Mumbai', 'assets/images/ec.jpg'),
(7, 1, 'The Siddhivinayak Temple', 'The Siddhivinayak Temple, located in Mumbai, India, is a revered Hindu shrine dedicated to Lord Ganesha. Built in 1801, it is known for its beautiful architecture and the idol of Ganesha with distinctive trunk positioning. The temple attracts millions of devotees, especially on Tuesdays, seeking blessings for prosperity and success. It is a symbol of faith and spirituality in Mumbai.', 'Mumbai', 'assets/images/svt.jpg'),
(13, 3, 'Humayun’s Tomb', 'Humayun’s Tomb, recognised as a UNESCO World Heritage Site in 1993, can be seen from all rooms and suites on one side of the best 5 star hotel in Delhi: The Oberoi, New Delhi. Started in 1565 but not completed until 1572, Humayun’s Tomb is believed to be the earliest example of Mughal architecture in India, and the inspiration for the Taj Mahal.\r\n\r\n', 'Delhi', 'assets/images/ht.jpg'),
(14, 3, 'India Gate.', 'India Gate is a magnificent sandstone memorial arch (42 metres high) engraved with the names of the 90,000 Indian soldiers who lost their lives in World War One. Standing tall and serene at one end of Rajpath, the memorial is a wonderful sight to behold from every aspect as you drive around the busy ring road that is India Gate Circle. Stand at India Gate looking down Rajpath and you will see the magnificent Rashtrapati Bhawan (the President’s House).', 'Delhi', 'assets/images/ig.jpg'),
(15, 3, 'Red Fort.', 'Red Fort (Lal Qila) is an architectural masterpiece; an ancient fort enclosed within vast red sandstone walls, from which it derives its name. The Red Fort was the main seat of the Mughal emperors for almost two centuries, as well as the ceremonial and political centre of the State. Inside the vast enclosure lie fortified gates, the imperial residences, a step well, baths and gardens.', 'Delhi', 'assets/images/rf.jpg'),
(16, 3, 'Akshardham Temple.', 'At Akshardham temple, ancient Indian architectural styles from across the subcontinent have been used to convey timeless traditions and spiritual messages. Spanning 100 acres, the temple complex includes a main monument which weighs 3,000 tonnes and features 148 scale sized elephants. There is also a hall of virtues, a theatre and a boat ride through 10,000 years of India’s heritage.', 'New Delhi', 'assets/images/at.jpg'),
(17, 4, 'Sidi Saiyyed Mosque', 'The Sidi Saiyyed Mosque, located in Ahmedabad, Gujarat, India, is a renowned architectural marvel built in 1573 by Sidi Saiyyed, a nobleman in the court of the last Sultan of Gujarat. Famous for its intricately carved stone latticework windows, known as jalis, the mosque features a particularly iconic design of intertwining tree and foliage motifs, which has become a symbol of Ahmedabad’s heritage. This mosque, a fine example of Indo-Islamic architecture, is admired for its artistic and historical significance.', 'Ahmedabad ,Gujrat', 'assets/images/sidi.jpg'),
(18, 4, 'Sabarmati Riverfront', '\r\nThe Sabarmati Riverfront is a major urban development project located along the Sabarmati River in Ahmedabad, Gujarat, India. The project aims to rejuvenate the riverfront by creating a series of parks, promenades, and recreational spaces, transforming the area into a vibrant public space. It includes a variety of amenities such as walking and cycling paths, gardens, amphitheaters, and boating facilities, making it a popular destination for locals and tourists alike. The Sabarmati Riverfront also plays a significant role in flood control and environmental management. It has become a symbol of Ahmedabad\'s modernization while preserving its cultural heritage.', 'Ahmedabad ,Gujrat', 'assets/images/sr.jpg'),
(19, 4, 'Mahatma Gandhi Sabarmati Ashram', 'The Mahatma Gandhi Sabarmati Ashram in Ahmedabad, Gujarat, was Gandhi\'s residence from 1917 to 1930. It served as a center for his work in India\'s freedom struggle and is now a museum showcasing his life, philosophy, and key events like the Salt March. The ashram remains a significant site for those inspired by his principles of non-violence and simplicity.', 'Ahmedabad ,Gujrat', 'assets/images/mgsa.jpg'),
(20, 4, 'Sardar Vallabhbhai Patel National Memorial', '\r\nThe Sardar Vallabhbhai Patel National Memorial is located in Ahmedabad, Gujarat, and honors the life and legacy of Sardar Vallabhbhai Patel, India\'s first Deputy Prime Minister and Home Minister. The memorial is housed in the Moti Shahi Mahal, a historic palace that once served as the residence of the Mughal governor. It showcases exhibits on Patel\'s role in India\'s independence movement, his efforts in uniting the nation, and his leadership during the integration of princely states. The memorial also includes a museum, an audio-visual gallery, and a statue of Patel, celebrating his contributions to India\'s unity and progress.', 'Ahmedabad ,Gujrat', 'assets/images/svpn.jpg'),
(21, 4, 'Kankaria Lake', 'Kankaria Lake is a large, man-made lake located in Ahmedabad, Gujarat. Originally built in the 15th century by Sultan Qutb-ud-Din, it has been transformed into a popular recreational spot. The lake is surrounded by a well-maintained promenade and offers various attractions, including a zoo, toy train, water park, and balloon ride. Kankaria Lake is a hub for leisure activities, picnics, and evening walks, making it one of the most visited places in Ahmedabad. It also hosts cultural events and festivals throughout the year.', 'Ahmedabad ,Gujrat', 'assets/images/kl.jpg'),
(22, 5, 'Basilica of bom jesus', 'The Basilica of Bom Jesus is a UNESCO World Heritage Site located in Old Goa, India. It is renowned for housing the mortal remains of St. Francis Xavier, one of the founding members of the Jesuit order. The church, built in 1605, is an excellent example of Baroque architecture and is known for its simple yet elegant design, with intricate woodwork and gilded altars. The basilica\'s significance lies in its religious and historical importance, as well as its role in the preservation of St. Francis Xavier\'s relics, which are displayed in a silver casket. It remains an important pilgrimage site for Christians.', 'Old Goa, Goa', 'assets/images/bj.jpg'),
(23, 5, 'Immaculate Conception Church', 'The Immaculate Conception Church, located in Panaji, Goa, is a historic and iconic Roman Catholic church built in 1541. Known for its striking white façade and grand staircase, the church is a fine example of Baroque architecture. It houses the revered statue of Our Lady of the Immaculate Conception and is a center of faith, especially during the Feast of the Immaculate Conception celebrated annually on December 8th. It is a popular tourist attraction and a symbol of Goa\'s Portuguese heritage.', 'Goa', 'assets/images/icc.jpg'),
(24, 5, 'Shree Mangueshi Temple', 'The Shree Mangueshi Temple is a prominent Hindu temple located in Priol, Goa, dedicated to Lord Manguesh, an incarnation of Lord Shiva. The temple, one of the largest and oldest in Goa, is known for its beautiful architecture, featuring a blend of traditional Goan and Konkani styles. The main structure includes a towering lamp post, an intricately carved entrance, and a large courtyard. The temple is an important pilgrimage site and is especially busy during the annual festival of Mangeshi, which attracts thousands of devotees. The serene and spiritual atmosphere makes it a significant cultural landmark in Goa.\r\n\r\n\r\n\r\n\r\n\r\n\r\n', 'Goa', 'assets/images/smt.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `nearby_resort`
--

CREATE TABLE `nearby_resort` (
  `place_id` int(11) NOT NULL,
  `resort_id` int(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `location` text NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nearby_resort`
--

INSERT INTO `nearby_resort` (`place_id`, `resort_id`, `name`, `description`, `location`, `image_url`) VALUES
(3, 1, 'Immaculate Conception Church', 'The Immaculate Conception Church, located in Panaji, Goa, is a historic and iconic Roman Catholic church built in 1541. Known for its striking white façade and grand staircase, the church is a fine example of Baroque architecture. It houses the revered statue of Our Lady of the Immaculate Conception and is a center of faith, especially during the Feast of the Immaculate Conception celebrated annually on December 8th. It is a popular tourist attraction and a symbol of Goa\'s Portuguese heritage.', 'Goa', 'assets/images/icc.jpg'),
(4, 1, 'Basilica of bom jesus', 'The Basilica of Bom Jesus is a UNESCO World Heritage Site located in Old Goa, India. It is renowned for housing the mortal remains of St. Francis Xavier, one of the founding members of the Jesuit order. The church, built in 1605, is an excellent example of Baroque architecture and is known for its simple yet elegant design, with intricate woodwork and gilded altars. The basilica\'s significance lies in its religious and historical importance, as well as its role in the preservation of St. Francis Xavier\'s relics, which are displayed in a silver casket. It remains an important pilgrimage site for Christians.', 'Old Goa, Goa', 'assets/images/bj.jpg'),
(5, 1, 'Shree Mangueshi Temple', 'The Shree Mangueshi Temple is a prominent Hindu temple located in Priol, Goa, dedicated to Lord Manguesh, an incarnation of Lord Shiva. The temple, one of the largest and oldest in Goa, is known for its beautiful architecture, featuring a blend of traditional Goan and Konkani styles. The main structure includes a towering lamp post, an intricately carved entrance, and a large courtyard. The temple is an important pilgrimage site and is especially busy during the annual festival of Mangeshi, which attracts thousands of devotees. The serene and spiritual atmosphere makes it a significant cultural landmark in Goa.', 'Gos', 'assets/images/smt.jpg'),
(6, 2, 'Lake Pichola', 'Lake Pichola is a stunning artificial freshwater lake located in Udaipur, Rajasthan, India. Built in 1362 AD, it is surrounded by picturesque hills, palaces, temples, and ghats. The lake is famous for its iconic landmarks, including the Lake Palace and Jag Mandir, which appear to float on its serene waters. Boat rides on the lake offer breathtaking views of the City Palace and other heritage structures. Lake Pichola is a symbol of Udaipur\'s romantic charm and remains one of the city\'s most popular tourist attractions.', 'Jaipur ,Rajasthan ', 'assets/images/lp.jpg'),
(7, 2, 'Hawa Mahal', 'The Hawa Mahal, or \"Palace of Winds,\" is an iconic monument in Jaipur, Rajasthan, India. Built in 1799 by Maharaja Sawai Pratap Singh, it features a unique five-story facade with 953 intricately carved windows (jharokhas). Designed in the shape of Lord Krishna’s crown, the structure allowed royal women to observe street activities while remaining unseen, adhering to the purdah system. Made of red and pink sandstone, Hawa Mahal is a fine example of Rajput architecture and a major tourist attraction symbolizing Jaipur\'s rich heritage.', 'Jaipur, Rajasthan ', 'assets/images/hm.jpg'),
(8, 2, 'Jaswant Thada', 'Jodhpur, the \"Blue City\" of Rajasthan, was founded in 1459 by Rao Jodha. Known for its blue-painted houses, it features the grand Mehrangarh Fort and the serene Jaswant Thada. Set in the Thar Desert, it showcases a rich blend of history, culture, and vibrant markets.', 'Jodhpur, Rajasthan ', 'assets/images/jt.jpg'),
(9, 3, 'Lonavala', 'Lonavala is a hill station surrounded by green valleys in western India near Mumbai. The Karla Caves and the Bhaja Caves are ancient Buddhist shrines carved out of the rock. They feature massive pillars and intricate relief sculptures. South of the Bhaja Caves sits the imposing Lohagad Fort, with its 4 gates. West of here is Bhushi Dam, where water overflows onto a set of steps during rainy season.', 'Pune, Maharashtra ', 'assets/images/lv.jpeg'),
(10, 3, 'Matheran', 'Matheran is a serene hill station located in Maharashtra, India, nestled in the Western Ghats at an elevation of 800 meters. Known for its lush greenery, cool climate, and pollution-free environment, it is Asia\'s only automobile-free hill station. Popular attractions include Panorama Point, Louisa Point, and Charlotte Lake, offering breathtaking views of valleys and waterfalls. Matheran is a perfect getaway for nature lovers and trekkers, accessible via a charming toy train ride.', 'Maharashtra ', 'assets/images/matheran.jpg'),
(11, 3, 'Gateway Of India', 'The Gateway of India is an arch-monument completed in 1924 on the waterfront of Mumbai, India. It was erected to commemorate the landing of George V for his coronation as the Emperor of India in December 1911 at Strand Road near Wellington Fountain. He was the first British monarch to visit India.', 'Mumbai ', 'assets/images/gate.jpg'),
(12, 3, 'Ajanta Caves', 'The Ajanta Caves, located in Maharashtra, India, are a UNESCO World Heritage Site renowned for their exquisite rock-cut architecture and ancient Buddhist art. Dating back to the 2nd century BCE to the 6th century CE, the caves comprise 30 intricately carved monasteries and temples. They feature stunning frescoes, sculptures, and paintings depicting the life of Buddha and Jataka tales. These caves are a masterpiece of Indian art and a testament to the skill of ancient craftsmen.', 'Maharashtra ', 'assets/images/aj.jpeg'),
(13, 4, 'The Laxmi Vilas Palace', 'The Laxmi Vilas Palace, located in Vadodara, Gujarat, is an architectural marvel and the residence of the Gaekwad royal family. Built in 1890 by Maharaja Sayajirao Gaekwad III, it is four times the size of Buckingham Palace and showcases a blend of Indo-Saracenic, European, and Mughal styles. The palace features stunning interiors, including ornate mosaics, chandeliers, and a grand Durbar Hall with Venetian glass. The complex also houses a golf course, museum, and lush gardens, making it a symbol of royal grandeur and cultural heritage.', 'Vadodara(Baroda), Gujrat', 'assets/images/lvp.jpg'),
(14, 4, 'Sayaji Baug(Kamati Baug)', 'Sayaji Baug, also known as Kamati Baug, is a sprawling public park in Vadodara, Gujarat. Built in 1879 by Maharaja Sayajirao Gaekwad III, it spans 113 acres and is one of the largest gardens in Western India. The park features lush greenery, fountains, and a variety of attractions, including the Baroda Museum and Picture Gallery, Sardar Patel Planetarium, and a toy train ride for children. It also houses a zoo and a floral clock. Sayaji Baug is a popular spot for recreation, education, and leisure, reflecting the city\'s rich heritage.', 'Vadodara(Baroda), Gujrat', 'assets/images/sbz.jpg'),
(15, 4, 'Baroda Museum and Picture Gallery', 'The Baroda Museum and Picture Gallery, located in Sayaji Baug, Vadodara, Gujarat, is a prominent cultural and historical landmark. Built in 1894 by Maharaja Sayajirao Gaekwad III, the museum houses a diverse collection of artifacts, including sculptures, manuscripts, textiles, and weapons. The adjoining Picture Gallery showcases European oil paintings and works by Indian artists. Highlights include an Egyptian mummy, Akota bronzes, and a skeleton of a blue whale. The museum offers a fascinating glimpse into art, history, and natural sciences, making it a must-visit destination for history and culture enthusiasts.', 'Vadodara(Baroda), Gujrat', 'assets/images/bm.jpeg'),
(16, 4, 'EME Temple', 'The EME Temple (also known as the Dakshinamurthy Temple) is a unique and modern temple located in Vadodara, Gujarat. It was constructed by the Indian Army\'s Electrical and Mechanical Engineering (EME) Corps in the 1960s, which is why it is often referred to as the EME Temple. The temple is known for its distinctive architecture, blending traditional and modern styles. It features a dome-shaped roof and is built using metal sheets, giving it a futuristic look. The temple is dedicated to Lord Dakshinamurthy, a form of Lord Shiva, and is a peaceful and spiritual place, surrounded by lush greenery.', 'Vadodara(Baroda), Gujrat', 'assets/images/eme.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `nearby_villa`
--

CREATE TABLE `nearby_villa` (
  `place_id` int(11) NOT NULL,
  `villa_id` int(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `location` text NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nearby_villa`
--

INSERT INTO `nearby_villa` (`place_id`, `villa_id`, `name`, `description`, `location`, `image_url`) VALUES
(2, 5, 'Mahatma Gandhi Museum', 'The Mahatma Gandhi Museum in Rajkot, Gujarat, is a historic site dedicated to the life and legacy of Mahatma Gandhi. Housed in the Alfred High School, where Gandhi studied, the museum showcases his journey, principles of non-violence, and key moments of India\'s freedom struggle through interactive exhibits and artifacts. It is a place of inspiration and learning for visitors worldwide.', 'Rajkot, Gujrat', 'assets/images/mgm.jpg'),
(3, 5, 'Khambhalida Caves', 'The Khambhalida Caves are ancient Buddhist rock-cut caves located near the town of Rajkot, Gujarat. These caves, dating back to the 4th century CE, are known for their intricate carvings and sculptures. The complex consists of three caves, with the main cave featuring a large stupa and several smaller shrines. The caves are believed to have been used by Buddhist monks for meditation and worship. The peaceful surroundings and the historical significance of the site make it a popular destination for history and archaeology enthusiasts.', 'Rajkot, Gujrat', 'assets/images/kmbc.jpg'),
(4, 5, 'Jubilee Garden', 'Jubilee Garden is a popular public park located in the heart of Rajkot, Gujarat. Established in 1908 to commemorate the silver jubilee of King George V’s reign, the garden is known for its lush greenery, well-maintained lawns, and peaceful atmosphere. It features walking paths, fountains, and a children\'s play area, making it a favorite spot for families and visitors looking to relax and enjoy nature. The garden also hosts cultural events and exhibitions, adding to its charm as a vibrant recreational space in the city.', 'Rajkot, Gujrat', 'assets/images/jgr.jpg'),
(5, 3, 'Shree Mangueshi Temple', 'The Shree Mangueshi Temple is a prominent Hindu temple located in Priol, Goa, dedicated to Lord Manguesh, an incarnation of Lord Shiva. The temple, one of the largest and oldest in Goa, is known for its beautiful architecture, featuring a blend of traditional Goan and Konkani styles. The main structure includes a towering lamp post, an intricately carved entrance, and a large courtyard. The temple is an important pilgrimage site and is especially busy during the annual festival of Mangeshi, which attracts thousands of devotees. The serene and spiritual atmosphere makes it a significant cultural landmark in Goa.', 'Goa', 'assets/images/smt.jpg'),
(6, 3, 'Basilica of bom jesus', 'The Basilica of Bom Jesus is a UNESCO World Heritage Site located in Old Goa, India. It is renowned for housing the mortal remains of St. Francis Xavier, one of the founding members of the Jesuit order. The church, built in 1605, is an excellent example of Baroque architecture and is known for its simple yet elegant design, with intricate woodwork and gilded altars. The basilica\'s significance lies in its religious and historical importance, as well as its role in the preservation of St. Francis Xavier\'s relics, which are displayed in a silver casket. It remains an important pilgrimage site for Christians.', 'Old Goa, Goa', 'assets/images/bj.jpg'),
(7, 3, 'Immaculate Conception Church', 'The Immaculate Conception Church, located in Panaji, Goa, is a historic and iconic Roman Catholic church built in 1541. Known for its striking white façade and grand staircase, the church is a fine example of Baroque architecture. It houses the revered statue of Our Lady of the Immaculate Conception and is a center of faith, especially during the Feast of the Immaculate Conception celebrated annually on December 8th. It is a popular tourist attraction and a symbol of Goa\'s Portuguese heritage.', 'Goa', 'assets/images/icc.jpg'),
(8, 4, 'Lake Pichola', 'Lake Pichola is a stunning artificial freshwater lake located in Udaipur, Rajasthan, India. Built in 1362 AD, it is surrounded by picturesque hills, palaces, temples, and ghats. The lake is famous for its iconic landmarks, including the Lake Palace and Jag Mandir, which appear to float on its serene waters. Boat rides on the lake offer breathtaking views of the City Palace and other heritage structures. Lake Pichola is a symbol of Udaipur\'s romantic charm and remains one of the city\'s most popular tourist attractions.', 'Jaipur, Rajasthan ', 'assets/images/lp.jpg'),
(9, 4, 'Hawa Mahal', 'The Hawa Mahal, or \"Palace of Winds,\" is an iconic monument in Jaipur, Rajasthan, India. Built in 1799 by Maharaja Sawai Pratap Singh, it features a unique five-story facade with 953 intricately carved windows (jharokhas). Designed in the shape of Lord Krishna’s crown, the structure allowed royal women to observe street activities while remaining unseen, adhering to the purdah system. Made of red and pink sandstone, Hawa Mahal is a fine example of Rajput architecture and a major tourist attraction symbolizing Jaipur\'s rich heritage.', 'Jaipur, Rajasthan ', 'assets/images/hm.jpg'),
(10, 4, 'Jaswant Thada', 'Jodhpur, the \"Blue City\" of Rajasthan, was founded in 1459 by Rao Jodha. Known for its blue-painted houses, it features the grand Mehrangarh Fort and the serene Jaswant Thada. Set in the Thar Desert, it showcases a rich blend of history, culture, and vibrant markets.', 'Jodhpur, Rajasthan ', 'assets/images/jt.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `resorts1`
--

CREATE TABLE `resorts1` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `features` text NOT NULL,
  `facilities` text NOT NULL,
  `guest_capacity` varchar(50) NOT NULL,
  `area` varchar(50) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `iframe` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resorts1`
--

INSERT INTO `resorts1` (`id`, `name`, `location`, `price`, `description`, `features`, `facilities`, `guest_capacity`, `area`, `image_url`, `iframe`) VALUES
(1, 'Dallas Resort', 'Goa', 2500.00, 'Dallas Beach Resorts, Ashvem Beach, North Goa is a serene beachfront resort offering comfortable accommodation with a relaxed, tropical vibe. Located near the pristine Ashvem Beach, it features well-appointed rooms, a restaurant serving local and international cuisine, and easy access to the beach, making it an ideal destination for a peaceful getaway in North Goa.', 'bedroom, balcony, sofa', 'AC, TV, Heater, Geyser', '5 Adults, 3 Children', '250 sq. ft.', 'assets\\images\\dallas.jpg', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d480.2504395642129!2d73.71871209999999!3d15.644801200000002!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bbfef691610ecaf%3A0xc44efdc9bbc50a88!2sDALLAS%20BEACH%20RESORTS%20ASHVEM%20BEACH%20NORTH%20GOA!5e0!3m2!1sen!2sin!4v1733832452275!5m2!1sen!2sin'),
(2, 'Rajasthali', 'Rajasthan ', 2500.00, 'Rajasthali Resort & Spa is a luxurious resort located in the heart of Rajasthan, offering a blend of traditional Rajasthani architecture and modern amenities. Surrounded by serene landscapes, it features spacious rooms, a rejuvenating spa, outdoor pools, and a range of dining options, making it a perfect destination for relaxation and cultural exploration.', 'bedroom, balcony, sofa', 'AC, TV, Heater, Geyser', '5 Adults, 3 Children', '250 sq. ft.', 'assets/images/rajresort.jpg', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3553.3268799464036!2d75.90242612212387!3d27.051428170664003!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x396daff37aaaaaab%3A0xe247584cc86c7d1e!2sRAJASTHALI%20RESORT%20%26%20SPA!5e0!3m2!1sen!2sin!4v1733832847085!5m2!1sen!2sin'),
(3, 'Grand Leela', 'Maharashtra', 2500.00, 'The Grand Leela Resort is a luxurious resort offering a perfect blend of elegance and comfort. Located in a scenic environment, it features spacious rooms, a variety of dining options, a relaxing spa, and recreational activities. Ideal for both leisure and business travelers, The Grand Leela promises a memorable and opulent stay.', 'bedroom, balcony, sofa', 'AC, TV, Heater, Geyser', '5 Adults, 3 Children', '250 sq. ft.', 'assets\\images\\leela.jpg', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3777.228768219692!2d73.35334257593169!3d18.787955560950618!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be8071db4462259%3A0xc6a960e35fb722d3!2sTHE%20GRAND%20LEELA%20%7C%20RESORT!5e0!3m2!1sen!2sin!4v1733833664470!5m2!1sen!2sin'),
(4, 'Prakruti Resort', 'Gujrat', 2500.00, 'Prakruti Resort is a tranquil retreat located near the serene beaches of North Goa. Surrounded by lush greenery, the resort offers spacious rooms, a multi-cuisine restaurant, a swimming pool, and various recreational activities. It provides a peaceful atmosphere, perfect for a relaxing getaway amidst nature.', 'bedroom, balcony, sofa', 'AC, TV, Heater, Geyser', '5 Adults, 3 Children', '250 sq. ft.', 'assets\\images\\gujrat.jpg', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1845.020431988838!2d73.16858034486604!3d22.352085829980194!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395fc94eaaaaaaab%3A0x199c555e9670454d!2sPrakruti%20Resort!5e0!3m2!1sen!2sin!4v1733833291900!5m2!1sen!2sin');

-- --------------------------------------------------------

--
-- Table structure for table `resort_booking`
--

CREATE TABLE `resort_booking` (
  `booking_id` int(11) NOT NULL,
  `room_id` varchar(255) NOT NULL,
  `roomname` varchar(255) NOT NULL,
  `rname` varchar(50) NOT NULL,
  `rprice` varchar(10) NOT NULL,
  `checkin` date NOT NULL,
  `checkout` date NOT NULL,
  `adult` int(10) NOT NULL,
  `child` int(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `userphno` varchar(50) NOT NULL,
  `useremail` varchar(50) NOT NULL,
  `payment_id` varchar(255) NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `status` varchar(20) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resort_booking`
--

INSERT INTO `resort_booking` (`booking_id`, `room_id`, `roomname`, `rname`, `rprice`, `checkin`, `checkout`, `adult`, `child`, `username`, `fullname`, `userphno`, `useremail`, `payment_id`, `order_id`, `status`) VALUES
(35, '1', 'Deluxe Room', 'Dallas Resort', '5000', '2025-03-04', '2025-03-06', 2, 1, 'Rajj_1632', 'Raj Patel', '8000247299', 'raj2584561@gmail.com', 'pay_PydUcDQrlUyXUo', 'order_PydUTngsyeBNlK', 'paid'),
(38, '5', 'Deluxe Room', 'Rajasthali', '6439.8', '2025-04-08', '2025-04-10', 2, 0, 'Rajj_1632', 'Raj Patel', '8238388031', 'raj2584561@gmail.com', 'pay_Q1QjaG4smh3nzT', 'order_Q1QjJknfBVKHYt', 'paid'),
(39, '8', 'Deluxe Room', 'Grand Leela', '3262.94', '2025-03-28', '2025-03-29', 2, 0, 'Rajj_1632', 'Raj Patel', '8238388031', 'raj2584561@gmail.com', 'pay_QA9Nao2RTaNAyz', 'order_QA9NR7ak7wnjNh', 'paid');

-- --------------------------------------------------------

--
-- Table structure for table `resort_managers`
--

CREATE TABLE `resort_managers` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resort_managers`
--

INSERT INTO `resort_managers` (`id`, `property_id`, `name`, `email`, `password`) VALUES
(7, 1, 'Raj', 'opever267@gmail.com', '$2y$10$Q2fE03M1jp9yGtDWChZ0aunpL5/osHhrqANAt7.hnvG2MJWOarVHq'),
(8, 2, 'raj', 'raj2584561@gmail.com', '$2y$10$Aq.1InG/bYEHgaA6DAhL3eih3sIMuDWUyRYl0m9aiQgEF47wE3Dyu'),
(9, 3, 'savan', 'savankaneriya03@gmail.com', '$2y$10$HULw5k7SpCROs8Hd/iH.v.BbvIq/wI5J.BNFPRitpz7GX.VuSCmOO'),
(11, 4, 'dev', 'devankani0@gamil.com', '$2y$10$bzpcYdkja3Zn6iLllnG4lenewQot7NlDb3gaUIsJwe4GqRCBv.oxG');

-- --------------------------------------------------------

--
-- Table structure for table `resort_reviews`
--

CREATE TABLE `resort_reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `property_id` int(11) DEFAULT NULL,
  `rating` float DEFAULT NULL CHECK (`rating` between 1 and 5),
  `review` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resort_reviews`
--

INSERT INTO `resort_reviews` (`id`, `user_id`, `property_id`, `rating`, `review`, `created_at`) VALUES
(1, 41, 1, 5, 'dsf ahfoq fhofh e fhq h', '2025-03-16 14:26:50'),
(2, 41, 1, 5, 'Excellent ', '2025-03-20 12:40:07'),
(3, 41, 1, 5, 'Nice', '2025-03-20 12:55:10'),
(4, 41, 1, 5, 'Nice', '2025-03-20 13:04:21'),
(5, 41, 1, 2, 'poor', '2025-03-20 13:07:25');

-- --------------------------------------------------------

--
-- Table structure for table `rooms1`
--

CREATE TABLE `rooms1` (
  `room_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `hotel_name` varchar(255) NOT NULL,
  `room_type` varchar(255) NOT NULL,
  `price_per_night` decimal(10,2) NOT NULL,
  `capacity` int(11) DEFAULT 1,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) NOT NULL,
  `360_image_url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms1`
--

INSERT INTO `rooms1` (`room_id`, `hotel_id`, `hotel_name`, `room_type`, `price_per_night`, `capacity`, `description`, `image_url`, `360_image_url`, `created_at`) VALUES
(1, 1, 'Taj Hotel', 'Deluxe Room', 2600.00, 2, 'Spacious room with a beautiful garden view', 'assets/images/deluxeroom.jpg', 'img/img1.jpg', '2024-12-04 17:33:28'),
(2, 1, 'Taj Hotel', 'Suite', 3161.79, 4, 'Luxury suite with a private pool and ocean view', 'assets/images/suite.jpg', 'img/img5.jpg', '2024-12-04 17:42:11'),
(4, 1, 'Taj Hotel', 'Family Suite', 3564.88, 5, 'Perfect for families with a spacious living area', 'assets/images/familysuite.jpg', 'img/img4.jpg', '2024-12-04 17:43:01'),
(8, 3, 'The Oberoi', 'Deluxe Room', 3121.23, 2, 'Spacious room with a beautiful garden view', 'assets/images/deluxeroom.jpg', 'img/img1.jpg', '2024-12-04 17:33:28'),
(9, 3, 'The Oberoi', 'Suite', 3096.22, 4, 'Luxury suite with a private pool and ocean view', 'assets/images/suite.jpg', 'img/img5.jpg', '2024-12-04 17:42:11'),
(10, 4, 'Regenta Hotel', 'Standard Room', 3060.94, 3, 'Cozy room with all basic amenities', 'assets/images/standardroom.jpg', 'img/img3.jpg', '2024-12-04 17:43:01'),
(11, 5, 'Ocean View', 'Standard Room', 3058.58, 3, 'Cozy room with all basic amenities', 'assets/images/standardroom.jpg', 'img/img3.jpg', '2024-12-04 17:43:01'),
(12, 4, 'Regenta Hotel', 'Family Suite', 3505.33, 5, 'Perfect for families with a spacious living area', 'assets/images/familysuite.jpg', 'img/img4.jpg', '2024-12-04 17:43:01'),
(13, 5, 'Ocean View', 'Suite', 3073.99, 4, 'Luxury suite with a private pool and ocean view', 'assets/images/suite.jpg', 'img/img5.jpg', '2024-12-04 17:42:11'),
(14, 4, 'Regenta Hotel', 'Deluxe Room', 3104.43, 2, 'Spacious room with a beautiful garden view', 'assets/images/deluxeroom.jpg', 'img/img1.jpg', '2024-12-04 17:33:28');

-- --------------------------------------------------------

--
-- Table structure for table `rooms2`
--

CREATE TABLE `rooms2` (
  `room_id` int(11) NOT NULL,
  `resort_id` int(11) NOT NULL,
  `resort_name` varchar(255) NOT NULL,
  `room_type` varchar(255) NOT NULL,
  `price_per_night` decimal(10,2) NOT NULL,
  `capacity` int(11) DEFAULT 1,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) NOT NULL,
  `360_image_url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms2`
--

INSERT INTO `rooms2` (`room_id`, `resort_id`, `resort_name`, `room_type`, `price_per_night`, `capacity`, `description`, `image_url`, `360_image_url`, `created_at`) VALUES
(1, 1, 'Dallas Resort', 'Deluxe Room', 3326.48, 2, 'Spacious room with a beautiful garden view', 'assets/images/deluxeroom.jpg', 'img/img1.jpg', '2024-12-04 17:33:28'),
(2, 1, 'Dallas Resort', 'Suite', 3813.97, 4, 'Luxury suite with a private pool and ocean view', 'assets/images/suite.jpg', 'img/img5.jpg', '2024-12-04 17:42:11'),
(4, 1, 'Dallas Resort', 'Family Suite', 3640.29, 5, 'Perfect for families with a spacious living area', 'assets/images/familysuite.jpg', 'img/img4.jpg', '2024-12-04 17:43:01'),
(5, 2, 'Rajasthali', 'Deluxe Room', 3313.22, 2, 'Spacious room with a beautiful garden view', 'assets/images/deluxeroom.jpg', 'img/img1.jpg', '2024-12-04 17:33:28'),
(6, 2, 'Rajasthali', 'Suite', 3799.33, 4, 'Luxury suite with a private pool and ocean view', 'assets/images/suite.jpg', 'img/img5.jpg', '2024-12-04 17:42:11'),
(7, 2, 'Rajasthali', 'Standard Room', 3486.48, 3, 'Cozy room with all basic amenities', 'assets/images/standardroom.jpg', 'img/img3.jpg', '2024-12-04 17:43:01'),
(8, 3, 'Grand Leela', 'Deluxe Room', 3262.94, 2, 'Spacious room with a beautiful garden view', 'assets/images/deluxeroom.jpg', 'img/img1.jpg', '2024-12-04 17:33:28'),
(9, 3, 'Grand Leela', 'Suite', 3726.61, 4, 'Luxury suite with a private pool and ocean view', 'assets/images/suite.jpg', 'img/img5.jpg', '2024-12-04 17:42:11'),
(10, 4, 'Prakruti Resort', 'Standard Room', 3447.29, 3, 'Cozy room with all basic amenities', 'assets/images/standardroom.jpg', 'img/img3.jpg', '2024-12-04 17:43:01'),
(12, 4, 'Prakruti Resort', 'Family Suite', 3583.43, 5, 'Perfect for families with a spacious living area', 'assets/images/familysuite.jpg', 'img/img4.jpg', '2024-12-04 17:43:01'),
(14, 4, 'Prakruti Resort', 'Deluxe Room', 3217.81, 2, 'Spacious room with a beautiful garden view', 'assets/images/deluxeroom.jpg', 'img/img1.jpg', '2024-12-04 17:33:28');

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `role` varchar(250) NOT NULL,
  `img_path` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`id`, `name`, `role`, `img_path`) VALUES
(1, 'Raj Patel', 'Co-Founder', 'assets/images/raj.jpg'),
(4, 'ATZ', 'Founder', 'assets/images/atz.jpg'),
(8, 'Savan Kaneriya', 'Co-Founder', 'assets/images/savan.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phno` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `password` varchar(500) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `fname`, `lname`, `email`, `phno`, `address`, `dob`, `password`, `reset_token`, `reset_expiry`) VALUES
(41, 'Rajj_1632', 'Raj', 'Patel', 'raj2584561@gmail.com', '8238388031', 'Bhavnagar', '2005-01-04', '$2y$10$x8FaOIv60ugnSXwPa1rnYOMvWFdjpcJdfTEn0LtqaE2i/dK/SiQI2', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_queries`
--

CREATE TABLE `user_queries` (
  `srno` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_queries`
--

INSERT INTO `user_queries` (`srno`, `name`, `email`, `subject`, `message`) VALUES
(4, 'Raj Patel', 'raj643995@gmail.com', 'Hotel', 'Your Hotel Booking Experience is too good specially that 360 degree view of a room one of the best booking site');

-- --------------------------------------------------------

--
-- Table structure for table `villas1`
--

CREATE TABLE `villas1` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `features` text NOT NULL,
  `facilities` text NOT NULL,
  `guest_capacity` varchar(50) NOT NULL,
  `area` varchar(50) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `iframe` varchar(2000) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `villas1`
--

INSERT INTO `villas1` (`id`, `name`, `location`, `price`, `description`, `features`, `facilities`, `guest_capacity`, `area`, `image_url`, `iframe`, `last_updated`) VALUES
(2, 'Beach Gate', 'Kerala', 2200.00, 'Beach Gate is an elegant coastal retreat offering luxurious accommodations just steps away from the beach. With modern amenities, serene surroundings, and personalized services, it provides an ideal setting for a relaxing seaside getaway, perfect for both leisure and rejuvenation.', 'bedroom, balcony, sofa', 'AC, TV, Heater, Geyser', '10', '500', 'assets/images/kerala.jpg', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1964.835061804356!2d76.23803084741192!3d9.961379548370207!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3b086d307e82d58b%3A0x7f88a35f6114c2cd!2sBeach%20Gate%20Bungalows%2C%20CGH%20Earth!5e0!3m2!1sen!2sin!4v1733838419224!5m2!1sen!2sin', '2025-03-23 08:43:21'),
(3, 'SaffronStays', 'Goa', 3200.00, 'SaffronStays is a curated collection of private vacation homes across scenic locations in India. Offering unique stays with personalized services, it’s ideal for families, friends, or couples seeking tranquil getaways. Each property features thoughtful amenities, beautiful decor, and a promise of exclusivity, ensuring memorable experiences.\r\n\r\n\r\n\r\n\r\n\r\n\r\n', 'bedroom, balcony, sofa', 'AC, TV, Heater, Geyser', '5', '450', 'assets/images/goa1.jpg', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3843.317918194743!2d73.77601016424119!3d15.574653920163197!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bbfeb20046b96eb%3A0x29fdd803a7f68b9b!2sSaffronStays%20Sweet%20Mango!5e0!3m2!1sen!2sin!4v1733838702316!5m2!1sen!2sin', '2025-03-23 08:43:32'),
(4, 'StayVista', 'Rajasthan ', 3000.00, 'StayVista is a premium vacation rental platform offering a wide range of handpicked luxury villas and holiday homes across India. Known for its personalized hospitality, stylish properties, and scenic locations, StayVista ensures a comfortable and memorable stay for families, friends, and travelers seeking exclusive getaways.', 'bedroom, balcony, sofa', 'AC, TV, Heater, Geyser', '12', '750', 'assets/images/udaipur.jpg', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3626.7387658066236!2d73.66272477604178!3d24.632687054332788!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3967e5fa12128f19%3A0xcd0682083412e4da!2sStayVista%20at%20Arna%20%7C%20Luxury%20Homestays%20with%20Private%20Pool%20in%20Udaipur!5e0!3m2!1sen!2sin!4v1733839363419!5m2!1sen!2sin', '2025-03-23 08:43:38'),
(5, 'Sunrise Villa', 'Gujrat', 1900.00, 'Sunrise Villa is a charming and serene retreat offering a cozy, home-like ambiance. Nestled amidst lush greenery, it features well-appointed rooms, modern amenities, and picturesque views, making it an ideal choice for a peaceful getaway with family or friends.', 'bedroom, balcony, sofa', 'AC, TV, Heater, Geyser', '8', '250 ', 'assets\\images\\sunrise.jpg', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d236328.820068901!2d70.27637757893304!3d22.253816738932546!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3959d3a4b46fe37d%3A0x824d2e71c7ea136d!2sSunrise%20Villa!5e0!3m2!1sen!2sin!4v1733838121776!5m2!1sen!2sin', '2025-03-23 08:43:14');

-- --------------------------------------------------------

--
-- Table structure for table `villa_booking`
--

CREATE TABLE `villa_booking` (
  `booking_id` int(11) NOT NULL,
  `vid` int(20) NOT NULL,
  `vname` varchar(50) NOT NULL,
  `vprice` varchar(10) NOT NULL,
  `checkin` date NOT NULL,
  `checkout` date NOT NULL,
  `adult` int(10) NOT NULL,
  `child` int(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `userphno` varchar(50) NOT NULL,
  `useremail` varchar(50) NOT NULL,
  `payment_id` varchar(255) NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `villa_booking`
--

INSERT INTO `villa_booking` (`booking_id`, `vid`, `vname`, `vprice`, `checkin`, `checkout`, `adult`, `child`, `username`, `fullname`, `userphno`, `useremail`, `payment_id`, `order_id`, `status`) VALUES
(46, 2, 'Beach Gate', '4800', '2025-03-11', '2025-03-13', 2, 1, 'Rajj_1632', 'Raj Patel', '8000247299', 'raj2584561@gmail.com', 'pay_PydW5G2Fzaochv', 'order_PydVvka8htuB0z', 'paid'),
(48, 2, 'Beach Gate', '4200', '2025-02-28', '2025-03-02', 2, 1, 'Rajj_1632', 'Raj Patel', '8238388031', 'raj2584561@gmail.com', 'pay_Q0RexmdLIXjM6H', 'order_Q0RenTkgrk0L7T', 'paid'),
(49, 2, 'Beach Gate', '4783.08', '2025-03-26', '2025-03-28', 2, 1, 'Rajj_1632', 'Raj Patel', '8238388031', 'raj2584561@gmail.com', 'pay_QAATpcfVJZH5Gk', 'order_QAATd27NgPiuG4', 'paid'),
(50, 3, 'SaffronStays', '4782.34', '2025-03-27', '2025-03-29', 2, 1, 'Rajj_1632', 'Raj Patel', '8238388031', 'raj2584561@gmail.com', 'pay_QAAV2yC4DGlSdr', 'order_QAAUtiz1mPvt4h', 'paid'),
(51, 4, 'StayVista', '4784.38', '2025-03-28', '2025-03-30', 8, 4, 'Rajj_1632', 'Raj Patel', '8238388031', 'raj2584561@gmail.com', 'pay_QAAZHOOejBwTpO', 'order_QAAZ7wn2m8FtKP', 'paid'),
(52, 5, 'Sunrise Villa', '3800', '2025-03-27', '2025-03-29', 2, 1, 'Rajj_1632', 'Raj Patel', '8238388031', 'raj2584561@gmail.com', 'pay_QAAj4z8Eb6CtGf', 'order_QAAixb2yYSbajI', 'paid'),
(53, 5, 'Sunrise Villa', '3800', '2025-03-28', '2025-03-30', 2, 1, 'Rajj_1632', 'Raj Patel', '8238388031', 'raj2584561@gmail.com', 'pay_QAAkLwJipbiRtD', 'order_QAAkFJTigBbPFG', 'paid');

-- --------------------------------------------------------

--
-- Table structure for table `villa_managers`
--

CREATE TABLE `villa_managers` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `villa_managers`
--

INSERT INTO `villa_managers` (`id`, `property_id`, `name`, `email`, `password`) VALUES
(6, 2, 'raj', 'raj2584561@gmail.com', '$2y$10$FmWspcUWYJniRes7pX81de6i3UsTLtry6oxDBQb2nr8lj3eCYhFwW'),
(7, 3, 'savan', 'savankaneriya03@gmail.com', '$2y$10$eFYYa8GCnl0pewhO.xSWoOPbLEDHN4YLiE8HCja.bvB3qF0QVIBny'),
(8, 4, 'dev', 'devankani0@gamil.com', '$2y$10$Et9xIfK6xAIh4yBABEuWzutsb9BnpW5BOck4P4.uqUaXYTsOx01wO'),
(10, 5, 'Raj', 'opever267@gmail.com', '$2y$10$D4CkXTe9kz16Nfols8OK4eHgjVQJO7aD2B6qNNKSeYGu6ENxmkBfS');

-- --------------------------------------------------------

--
-- Table structure for table `villa_reviews`
--

CREATE TABLE `villa_reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `property_id` int(11) DEFAULT NULL,
  `rating` float DEFAULT NULL CHECK (`rating` between 1 and 5),
  `review` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `villa_reviews`
--

INSERT INTO `villa_reviews` (`id`, `user_id`, `property_id`, `rating`, `review`, `created_at`) VALUES
(6, 41, 2, 4, 'n sifif gffig  f sffjsfhg sdf  gsfgsdgsd gf sdg sjg fsgf s ssg dsjgf jdsdgfsja sg as jgasjhfg sjfg sjgfsjhg sj gjsgfasjg fsjf gsj fg', '2025-03-16 14:28:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `cab_booking_hotel`
--
ALTER TABLE `cab_booking_hotel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_hotel_booking` (`hotel_booking_id`);

--
-- Indexes for table `cab_booking_resort`
--
ALTER TABLE `cab_booking_resort`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_resort_booking` (`resort_booking_id`);

--
-- Indexes for table `carousel_images`
--
ALTER TABLE `carousel_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hotels1`
--
ALTER TABLE `hotels1`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_hotel_name` (`name`);

--
-- Indexes for table `hotel_booking`
--
ALTER TABLE `hotel_booking`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `hotel_managers`
--
ALTER TABLE `hotel_managers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `hotel_reviews`
--
ALTER TABLE `hotel_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `hotel_id` (`property_id`);

--
-- Indexes for table `nearby_hotel`
--
ALTER TABLE `nearby_hotel`
  ADD PRIMARY KEY (`place_id`),
  ADD KEY `fk_h_id` (`hotel_id`);

--
-- Indexes for table `nearby_resort`
--
ALTER TABLE `nearby_resort`
  ADD PRIMARY KEY (`place_id`),
  ADD KEY `fk_r_id` (`resort_id`);

--
-- Indexes for table `nearby_villa`
--
ALTER TABLE `nearby_villa`
  ADD PRIMARY KEY (`place_id`),
  ADD KEY `fk_v_id` (`villa_id`);

--
-- Indexes for table `resorts1`
--
ALTER TABLE `resorts1`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `resort_booking`
--
ALTER TABLE `resort_booking`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `resort_managers`
--
ALTER TABLE `resort_managers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `resort_reviews`
--
ALTER TABLE `resort_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `resort_id` (`property_id`);

--
-- Indexes for table `rooms1`
--
ALTER TABLE `rooms1`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `rooms2`
--
ALTER TABLE `rooms2`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `resort_id` (`resort_id`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `phno` (`phno`);

--
-- Indexes for table `user_queries`
--
ALTER TABLE `user_queries`
  ADD PRIMARY KEY (`srno`);

--
-- Indexes for table `villas1`
--
ALTER TABLE `villas1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `villa_booking`
--
ALTER TABLE `villa_booking`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `villa_managers`
--
ALTER TABLE `villa_managers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `villa_reviews`
--
ALTER TABLE `villa_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `villa_id` (`property_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `cab_booking_hotel`
--
ALTER TABLE `cab_booking_hotel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `cab_booking_resort`
--
ALTER TABLE `cab_booking_resort`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `carousel_images`
--
ALTER TABLE `carousel_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `hotels1`
--
ALTER TABLE `hotels1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `hotel_booking`
--
ALTER TABLE `hotel_booking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `hotel_managers`
--
ALTER TABLE `hotel_managers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `hotel_reviews`
--
ALTER TABLE `hotel_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `nearby_hotel`
--
ALTER TABLE `nearby_hotel`
  MODIFY `place_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `nearby_resort`
--
ALTER TABLE `nearby_resort`
  MODIFY `place_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `nearby_villa`
--
ALTER TABLE `nearby_villa`
  MODIFY `place_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `resorts1`
--
ALTER TABLE `resorts1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `resort_booking`
--
ALTER TABLE `resort_booking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `resort_managers`
--
ALTER TABLE `resort_managers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `resort_reviews`
--
ALTER TABLE `resort_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rooms1`
--
ALTER TABLE `rooms1`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `rooms2`
--
ALTER TABLE `rooms2`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `user_queries`
--
ALTER TABLE `user_queries`
  MODIFY `srno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `villas1`
--
ALTER TABLE `villas1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `villa_booking`
--
ALTER TABLE `villa_booking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `villa_managers`
--
ALTER TABLE `villa_managers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `villa_reviews`
--
ALTER TABLE `villa_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cab_booking_hotel`
--
ALTER TABLE `cab_booking_hotel`
  ADD CONSTRAINT `fk_hotel_booking` FOREIGN KEY (`hotel_booking_id`) REFERENCES `hotel_booking` (`booking_id`) ON DELETE CASCADE;

--
-- Constraints for table `cab_booking_resort`
--
ALTER TABLE `cab_booking_resort`
  ADD CONSTRAINT `fk_resort_booking` FOREIGN KEY (`resort_booking_id`) REFERENCES `resort_booking` (`booking_id`) ON DELETE CASCADE;

--
-- Constraints for table `hotel_managers`
--
ALTER TABLE `hotel_managers`
  ADD CONSTRAINT `hotel_managers_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `hotels1` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hotel_reviews`
--
ALTER TABLE `hotel_reviews`
  ADD CONSTRAINT `hotel_reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hotel_reviews_ibfk_2` FOREIGN KEY (`property_id`) REFERENCES `hotels1` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nearby_hotel`
--
ALTER TABLE `nearby_hotel`
  ADD CONSTRAINT `fk_h_id` FOREIGN KEY (`hotel_id`) REFERENCES `hotels1` (`id`),
  ADD CONSTRAINT `fk_hotel_id` FOREIGN KEY (`hotel_id`) REFERENCES `hotels1` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nearby_resort`
--
ALTER TABLE `nearby_resort`
  ADD CONSTRAINT `fk_r_id` FOREIGN KEY (`resort_id`) REFERENCES `resorts1` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_resort_id` FOREIGN KEY (`resort_id`) REFERENCES `resorts1` (`id`);

--
-- Constraints for table `nearby_villa`
--
ALTER TABLE `nearby_villa`
  ADD CONSTRAINT `fk_v_id` FOREIGN KEY (`villa_id`) REFERENCES `villas1` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_villa_id` FOREIGN KEY (`villa_id`) REFERENCES `villas1` (`id`);

--
-- Constraints for table `resort_managers`
--
ALTER TABLE `resort_managers`
  ADD CONSTRAINT `resort_managers_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `resorts1` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `resort_reviews`
--
ALTER TABLE `resort_reviews`
  ADD CONSTRAINT `resort_reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `resort_reviews_ibfk_2` FOREIGN KEY (`property_id`) REFERENCES `resorts1` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rooms1`
--
ALTER TABLE `rooms1`
  ADD CONSTRAINT `rooms1_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels1` (`id`);

--
-- Constraints for table `rooms2`
--
ALTER TABLE `rooms2`
  ADD CONSTRAINT `fk_resorts1` FOREIGN KEY (`resort_id`) REFERENCES `resorts1` (`id`);

--
-- Constraints for table `villa_managers`
--
ALTER TABLE `villa_managers`
  ADD CONSTRAINT `villa_managers_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `villas1` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `villa_reviews`
--
ALTER TABLE `villa_reviews`
  ADD CONSTRAINT `villa_reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `villa_reviews_ibfk_2` FOREIGN KEY (`property_id`) REFERENCES `villas1` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
