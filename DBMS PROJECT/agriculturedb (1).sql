-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 29, 2024 at 06:14 PM
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
-- Database: `agriculturedb`
--

-- --------------------------------------------------------

--
-- Table structure for table `agricultureinstruments`
--

CREATE TABLE `agricultureinstruments` (
  `instrument_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `instrument_type` varchar(100) NOT NULL,
  `serial_number` varchar(100) NOT NULL,
  `installation_date` date NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `agricultureinstruments`
--

INSERT INTO `agricultureinstruments` (`instrument_id`, `field_id`, `instrument_type`, `serial_number`, `installation_date`, `status`) VALUES
(1, 0, 'ssdvsv', 'svsdvd', '2024-09-02', 'Active'),
(2, 1, 'ssdvsvret', 'rett43533', '2024-09-29', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `crops`
--

CREATE TABLE `crops` (
  `crop_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `crop_name` varchar(100) NOT NULL,
  `growth_duration` int(11) NOT NULL,
  `water_needs` varchar(50) NOT NULL,
  `sunlight_needs` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `crops`
--

INSERT INTO `crops` (`crop_id`, `field_id`, `crop_name`, `growth_duration`, `water_needs`, `sunlight_needs`) VALUES
(1, 1, 'white', 2, 'High', 'Full Sun'),
(2, 1, 'blue', 24, 'Low', 'Partial Sun');

-- --------------------------------------------------------

--
-- Table structure for table `farmers`
--

CREATE TABLE `farmers` (
  `farmer_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `farmers`
--

INSERT INTO `farmers` (`farmer_id`, `name`, `contact`, `email`, `address`) VALUES
(1, 'manvanth', '234325435435432', '234324324@gmail.com', 'rtreger'),
(2, 'anish', '12343', 'anish@gmail.com', 'retgtg54tg');

-- --------------------------------------------------------

--
-- Table structure for table `fields`
--

CREATE TABLE `fields` (
  `field_id` int(11) NOT NULL,
  `farmer_id` int(11) NOT NULL,
  `field_name` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL,
  `size_in_acres` float NOT NULL,
  `soil_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `fields`
--

INSERT INTO `fields` (`field_id`, `farmer_id`, `field_name`, `location`, `size_in_acres`, `soil_type`) VALUES
(1, 0, 'dfgdf', 'bdfb', 1, 'xbfb'),
(2, 2, 'dfgdf', 'gdg', 32, 'wet');

-- --------------------------------------------------------

--
-- Table structure for table `sensordata`
--

CREATE TABLE `sensordata` (
  `data_id` int(11) NOT NULL,
  `instrument_id` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  `data_type` varchar(50) NOT NULL,
  `value` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `sensordata`
--

INSERT INTO `sensordata` (`data_id`, `instrument_id`, `timestamp`, `data_type`, `value`) VALUES
(1, 0, '2024-09-04 20:34:00', 'Humidity', 56),
(2, 2, '2024-09-05 21:07:00', 'Humidity', 4234);

-- --------------------------------------------------------

--
-- Table structure for table `weatherdata`
--

CREATE TABLE `weatherdata` (
  `weather_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `temperature` float NOT NULL,
  `rainfall` float NOT NULL,
  `humidity` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `weatherdata`
--

INSERT INTO `weatherdata` (`weather_id`, `field_id`, `date`, `temperature`, `rainfall`, `humidity`) VALUES
(1, 0, '2024-09-03', 0.5, 0.3, 0.3),
(2, 1, '2024-09-07', 345, 34, 345);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agricultureinstruments`
--
ALTER TABLE `agricultureinstruments`
  ADD PRIMARY KEY (`instrument_id`),
  ADD UNIQUE KEY `serial_number` (`serial_number`),
  ADD KEY `field_id` (`field_id`);

--
-- Indexes for table `crops`
--
ALTER TABLE `crops`
  ADD PRIMARY KEY (`crop_id`),
  ADD KEY `field_id` (`field_id`);

--
-- Indexes for table `farmers`
--
ALTER TABLE `farmers`
  ADD PRIMARY KEY (`farmer_id`);

--
-- Indexes for table `fields`
--
ALTER TABLE `fields`
  ADD PRIMARY KEY (`field_id`),
  ADD KEY `farmer_id` (`farmer_id`);

--
-- Indexes for table `sensordata`
--
ALTER TABLE `sensordata`
  ADD PRIMARY KEY (`data_id`),
  ADD KEY `instrument_id` (`instrument_id`);

--
-- Indexes for table `weatherdata`
--
ALTER TABLE `weatherdata`
  ADD PRIMARY KEY (`weather_id`),
  ADD KEY `field_id` (`field_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agricultureinstruments`
--
ALTER TABLE `agricultureinstruments`
  MODIFY `instrument_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `crops`
--
ALTER TABLE `crops`
  MODIFY `crop_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `farmers`
--
ALTER TABLE `farmers`
  MODIFY `farmer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `fields`
--
ALTER TABLE `fields`
  MODIFY `field_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sensordata`
--
ALTER TABLE `sensordata`
  MODIFY `data_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `weatherdata`
--
ALTER TABLE `weatherdata`
  MODIFY `weather_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `agricultureinstruments`
--
ALTER TABLE `agricultureinstruments`
  ADD CONSTRAINT `agricultureinstruments_ibfk_1` FOREIGN KEY (`field_id`) REFERENCES `fields` (`field_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `crops`
--
ALTER TABLE `crops`
  ADD CONSTRAINT `crops_ibfk_1` FOREIGN KEY (`field_id`) REFERENCES `fields` (`field_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fields`
--
ALTER TABLE `fields`
  ADD CONSTRAINT `fields_ibfk_1` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`farmer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sensordata`
--
ALTER TABLE `sensordata`
  ADD CONSTRAINT `sensordata_ibfk_1` FOREIGN KEY (`instrument_id`) REFERENCES `agricultureinstruments` (`instrument_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `weatherdata`
--
ALTER TABLE `weatherdata`
  ADD CONSTRAINT `weatherdata_ibfk_1` FOREIGN KEY (`field_id`) REFERENCES `fields` (`field_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
