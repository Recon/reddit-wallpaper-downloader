<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1456426082.
 * Generated on 2016-02-25 19:48:02 
 */
class PropelMigration_1456426082
{
    public $comment = '';

    public function preUp($manager)
    {
        // add the pre-migration code here
    }

    public function postUp($manager)
    {
        // add the post-migration code here
    }

    public function preDown($manager)
    {
        // add the pre-migration code here
    }

    public function postDown($manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
  'default' => '
PRAGMA foreign_keys = OFF;

CREATE TABLE [records]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [reddit_post_name] VARCHAR(10) NOT NULL,
    [subreddit_name] VARCHAR(255) NOT NULL,
    [media_url] VARCHAR(255) NOT NULL,
    [author] VARCHAR(90),
    [title] VARCHAR(511),
    [is_crawled] INTEGER DEFAULT 0,
    [created_at] TIMESTAMP,
    UNIQUE ([id])
);

PRAGMA foreign_keys = ON;
',
);
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
  'default' => '
PRAGMA foreign_keys = OFF;

DROP TABLE IF EXISTS [records];

PRAGMA foreign_keys = ON;
',
);
    }

}