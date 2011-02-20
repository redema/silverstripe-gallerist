
ALTER TABLE `GalleristPageItem` DROP `ImageID`;
ALTER TABLE `GalleristPageItem` CHANGE `FileID` `ImageID` INT(11) NOT NULL DEFAULT '0';

ALTER TABLE `GalleristPageItem` DROP INDEX `FileID`, ADD INDEX `ImageID`(`ImageID`);

ALTER TABLE `GalleristPageItem_Live` DROP `ImageID`;
ALTER TABLE `GalleristPageItem_Live` CHANGE `FileID` `ImageID` INT(11) NOT NULL DEFAULT '0';

ALTER TABLE `GalleristPageItem_Live` DROP INDEX `FileID`, ADD INDEX `ImageID`(`ImageID`);

ALTER TABLE `GalleristPageItem_versions` DROP `ImageID`;
ALTER TABLE `GalleristPageItem_versions` CHANGE `FileID` `ImageID` INT(11) NOT NULL DEFAULT '0';

ALTER TABLE `GalleristPageItem_versions` DROP INDEX `FileID`, ADD INDEX `ImageID`(`ImageID`);

