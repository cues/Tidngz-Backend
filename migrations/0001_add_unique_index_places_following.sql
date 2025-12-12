-- Migration: Add unique index on Places_Following(PLACE_ID, `USER`)
-- Run this once against your Tidngz database to prevent duplicate follow rows.
-- Database: `Tidngz` (replace if you use a different database name when running via the mysql CLI)

-- This script creates a short stored procedure which will add the index only if it does not already exist,
-- then calls the procedure and drops it. This avoids ALTER TABLE errors when the index already exists.

DELIMITER $$

DROP PROCEDURE IF EXISTS add_uniq_place_user$$
CREATE PROCEDURE add_uniq_place_user()
BEGIN
  IF (SELECT COUNT(*) FROM information_schema.statistics
        WHERE table_schema = DATABASE()
          AND table_name = 'Places_Following'
          AND index_name = 'uniq_place_user') = 0 THEN
    ALTER TABLE Places_Following
      ADD UNIQUE INDEX uniq_place_user (PLACE_ID, `USER`);
  END IF;
END$$

DELIMITER ;

CALL add_uniq_place_user();

DROP PROCEDURE IF EXISTS add_uniq_place_user;

-- End of migration
