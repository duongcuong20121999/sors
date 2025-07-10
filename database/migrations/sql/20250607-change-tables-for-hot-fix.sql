ALTER TABLE `citizen_services`
ADD COLUMN `appointment_start_date`  datetime NULL AFTER `created_date`;

ALTER TABLE `citizen_services`
ADD COLUMN `source`  varchar(10) NULL AFTER `updated_at`;

ALTER TABLE `citizen_services`
ADD COLUMN `read`  tinyint(4) NOT NULL DEFAULT 0 AFTER `source`;
