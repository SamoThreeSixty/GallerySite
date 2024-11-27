# GallerySite
Small gallery site to demonstrate CRUD functions

URLs:
- https://sambradshaw.thinkap.co.uk/gallery.php
- https://sambradshaw.thinkap.co.uk/admin.php

# Dependences
This should be created with pure PHP and no use of frameworks.
Javascript and JQuery can be used where beneficial, however it is not essential.
Do not remove data, rather add new records and set the Live_Flag, and removing it should set the Delete_Flag.
MySQL will be used for the database.

- PHP v8.3.12
- MySQL
- JQuery 3.7.1

# SQL Table
TABLE `Staff` (
    `Id` INT(11) NOT NULL AUTO_INCREMENT,
    `Staff_Id` INT(11) NOT NULL DEFAULT '0',
    `Staff_Name` VARCHAR(100) NOT NULL DEFAULT '',
    `Image_Filename` VARCHAR(255) NOT NULL DEFAULT '',
    `Position` INT(11) NOT NULL DEFAULT '0',
    `Live_Flag` VARCHAR(1) NOT NULL DEFAULT 'Y',
    `Added_Date_Time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `Added_From_IP` VARCHAR(20) NOT NULL DEFAULT '',
    `Deleted_Flag` VARCHAR(1) NOT NULL DEFAULT 'N',
    `Deleted_Date_Time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `Deleted_From_IP` VARCHAR(20) NOT NULL DEFAULT '',
    PRIMARY KEY (`Id`) USING BTREE,
    INDEX `IX_DELETED` (`Deleted_Flag`) USING BTREE,
    INDEX `IX_LIVE` (`Live_Flag`) USING BTREE,
    INDEX `IX_STAFF` (`Staff_Id`) USING BTREE,
    INDEX `IX_LIVE_STAFF` (`Live_Flag`, `Staff_Id`) USING BTREE
)


## File structure
#### admin.php
- [x] Option to upload images and save the details to a MySQL database. 
- [x] Any uploaded content (file and text) should be validated and sanitised.
- [x] Images should be resized to the correct width.
- [x] Ideally multiple images should be created, one for mobile, tablet and desktop.
- [x] Ability to move the image up and down in the list.
- [x] Ability to amend and remove staff details.

#### gallery.php
- [x] Display all the images stored in the MySQL database in the correct order.
- [x] Ideally the correct image width (mobile, tablet, desktop) should be served to the correct device.