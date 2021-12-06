CREATE TABLE `Version` (
  `VersionID` int(10) NOT NULL,
  `VersionNum` varchar(45) DEFAULT NULL,
  `Createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Deprecate` enum('Y','N') DEFAULT 'N',
  `PackageName` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `Version` (`VersionID`, `VersionNum`, `Createtime`, `Deprecate`, `PackageName`) VALUES
(1, '1', '2021-12-6 14:34:53', 'N', 'feweb'),
(2, '1', '2021-12-6 14:34:58', 'N', 'fephp'),
(3, '1', '2021-12-6 14:35:01', 'N', 'db'),
(4, '1', '2021-12-6 14:35:05', 'N', 'bephp');

ALTER TABLE `Version`
  ADD PRIMARY KEY (`VersionID`,`PackageName`),
  ADD KEY `Version_Package_PackageName_idx` (`PackageName`);

ALTER TABLE `Version`
  MODIFY `VersionID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

ALTER TABLE `Version`
  ADD CONSTRAINT `Version_Package_PackageName` FOREIGN KEY (`PackageName`) REFERENCES `Package` (`PackageName`) ON DELETE NO ACTION ON UPDATE NO ACTION;
