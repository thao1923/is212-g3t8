
drop database if exists spm_database;
create database spm_database;
use spm_database;
--
-- Database: `spm_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `bid`
--

DROP TABLE IF EXISTS `bid`;
CREATE TABLE IF NOT EXISTS `bid` (
  `userid` varchar(128) NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `course_code` varchar(30) NOT NULL,
  `section` varchar(3) NOT NULL,
  `status` varchar(10),
  PRIMARY KEY (`userid`,`course_code`,`section`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

DROP TABLE IF EXISTS `course`;
CREATE TABLE IF NOT EXISTS `course` (
  `course_code` varchar(30) NOT NULL,
  `school` varchar(50) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `exam_date` date NOT NULL,
  `exam_start` time NOT NULL,
  `exam_end` time NOT NULL,
  PRIMARY KEY (`course_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `course_completed`
--

DROP TABLE IF EXISTS `course_completed`;
CREATE TABLE IF NOT EXISTS `course_completed` (
  `userid` varchar(128) NOT NULL,
  `course_code` varchar(30) NOT NULL,
  PRIMARY KEY (`userid`,`course_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `prerequisite`
--

DROP TABLE IF EXISTS `prerequisite`;
CREATE TABLE IF NOT EXISTS `prerequisite` (
  `course_code` varchar(30) NOT NULL,
  `prerequisite` varchar(30) NOT NULL,
  PRIMARY KEY (`course_code`,`prerequisite`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

DROP TABLE IF EXISTS `section`;
CREATE TABLE IF NOT EXISTS `section` (
  `course_code` varchar(30) NOT NULL,
  `section` varchar(5) NOT NULL,
  `day` int(11) DEFAULT NULL,
  `start` time DEFAULT NULL,
  `end` time DEFAULT NULL,
  `instructor` varchar(100) DEFAULT NULL,
  `venue` varchar(50) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  PRIMARY KEY (`course_code`,`section`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
CREATE TABLE IF NOT EXISTS `student` (
  `userid` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `name` varchar(100) NOT NULL,
  `school` varchar(50) NOT NULL,
  `edollar` decimal(10,2) NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
COMMIT;


DROP TABLE IF EXISTS `successful_bid`;
CREATE TABLE IF NOT EXISTS `successful_bid` (
  `userid` varchar(128) NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `course_code` varchar(30) NOT NULL,
  `section` varchar(3) NOT NULL,
  `round` varchar(3) NOT NULL,
  PRIMARY KEY (`userid`,`course_code`,`section`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `round_no`;
CREATE TABLE IF NOT EXISTS `round_no`(
`round_no` decimal(10,1) NOT NULL,
PRIMARY KEY (`round_no`))
ENGINE=InnoDB DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS `min_bid`;
CREATE TABLE IF NOT EXISTS `min_bid`(
  `course_code` varchar(30) NOT NULL,
  `section` varchar(3) NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,

PRIMARY KEY (`course_code`,`section`))
ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `unsuccessful_bid`;
CREATE TABLE IF NOT EXISTS `unsuccessful_bid` (
  `userid` varchar(128) NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `course_code` varchar(30) NOT NULL,
  `section` varchar(3) NOT NULL,
  PRIMARY KEY (`userid`,`course_code`,`section`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;





