<?php
/** Create DB Folder if not existing yet */
if(!is_dir(__DIR__.'./db'))
    mkdir(__DIR__.'./db');
/** Define DB File Path */
if(!defined('db_file')) define('db_file',__DIR__.'./db/forum_db.db');
/** Define DB File Path */
if(!defined('tZone')) define('tZone',"Asia/Manila");
if(!defined('dZone')) define('dZone',ini_get('date.timezone'));

/** DB Connection Class */
Class DBConnection extends SQLite3{
    protected $db;
    function __construct(){
        /** Opening Database */
        $this->open(db_file);
        $this->exec("PRAGMA foreign_keys = ON;");
        /** Closing Database */
        $this->exec("CREATE TABLE IF NOT EXISTS `user_list` (
            `user_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `fullname` INTEGER NOT NULL,
            `username` TEXT NOT NULL,
            `password` TEXT NOT NULL,
            `type` TINYINT(1) NOT NULL Default 0,
            `status` TINYINT(1) NOT NULL Default 0,
            `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"); 
        $this->exec("CREATE TABLE IF NOT EXISTS `topic_list` (
            `topic_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `user_id` INTEGER NOT NULL,
            `title` TEXT NOT NULL,
            `description` TEXT NOT NULL,
            `status` TINYINT(2) NOT NULL Default 0,
            `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(`user_id`) REFERENCES `user_list`(`user_id`)
        )");
        $this->exec("CREATE TABLE IF NOT EXISTS `comment_list` (
            `comment_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            `topic_id` INTEGER NOT NULL,
            `user_id` INTEGER NOT NULL,
            `comment` TEXT NOT NULL,
            `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(`topic_id`) REFERENCES `topic_list`(`topic_id`)
        )");
        $this->exec("INSERT OR IGNORE INTO `user_list` VALUES (1, 'Administrator', 'admin', '$2y$10\$Aj/jjNbcT1vNZrp.9ELpheF9rgjP9RInWb8RSuTGAKcoKJE26HCb6', 1, 1, CURRENT_TIMESTAMP)");

    }
    function __destruct(){
         $this->close();
    }
}

$conn = new DBConnection();