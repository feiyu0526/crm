ALTER TABLE `marketing_sms_campaign` ADD `type_envoi` INT NOT NULL AFTER `last_update`;

ALTER TABLE `marketing_sms_campaign`
  DROP `estimation_nombre`;
  
  
  ALTER TABLE `oc_users` ADD `is_facturation` INT NOT NULL DEFAULT '1' AFTER `is_sms`;