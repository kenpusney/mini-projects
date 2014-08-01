#!/usr/bin/ruby

require 'sqlite3'
if not File.exist?('note.db')
    db = SQLite3::Database.new('note.db')
#Init 'Note'
    db.query('
            CREATE TABLE "main"."note" (
                "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                "title" TEXT NOT NULL,
                "content" TEXT NOT NULL,
                "add_time" TEXT NOT NULL,
                "last_modified" TEXT NOT NULL
            );')
#Init 'Tag'
    db.query('
            CREATE TABLE "main"."tag" (
                "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                "tag" TEXT NOT NULL,
                "description" TEXT,
                "uri" TEXT NOT NULL
            );')
#Init 'Comments'
    db.query('
            CREATE TABLE "main"."comments" (
                "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                "comments" TEXT NOT NULL,
                "note_id" INTEGER NOT NULL
            );')
#Init 'Note<->Tag'
    db.query('
            CREATE TABLE "main"."note_tag" (
                "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                "tag_id" INTEGER NOT NULL,
                "note_id" INTEGER NOT NULL
            );')
end

db = SQLite3::Database.new('note.db')
db.results_as_hash = true


