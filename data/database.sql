CREATE TABLE student (
student_id     INT(9) NOT NULL,
student_name   VARCHAR(30) NOT NULL, 
student_pass   VARCHAR(255) NOT NULL,
student_date   DATETIME NOT NULL,
student_level  INT(8) NOT NULL DEFAULT 0,
PRIMARY KEY (student_id)
);

CREATE TABLE availableStudent (
student_id     INT(9) NOT NULL
);

CREATE TABLE admin (
admin_id     INT(8) NOT NULL,
admin_name   VARCHAR(30) NOT NULL, 
admin_pass   VARCHAR(255) NOT NULL,
admin_date   DATETIME NOT NULL,
UNIQUE INDEX admin_name_unique (admin_name),
PRIMARY KEY (admin_id)
);

CREATE TABLE topics (
topic_id        INT(8) NOT NULL AUTO_INCREMENT,
topic_subject   VARCHAR(255) NOT NULL,
topic_date      DATETIME NOT NULL,
topic_cat       CHAR(8) NOT NULL,
topic_by        INT(8) NOT NULL,
UNIQUE INDEX topic_subject_unique (topic_subject),
PRIMARY KEY (topic_id)
);

CREATE TABLE admintopics (
topic_id        INT(8) NOT NULL AUTO_INCREMENT,
topic_subject   VARCHAR(255) NOT NULL,
topic_date      DATETIME NOT NULL,
topic_cat       CHAR(8) NOT NULL,
topic_by        INT(8) NOT NULL,
UNIQUE INDEX topic_subject_unique (topic_subject),
PRIMARY KEY (topic_id)
);

CREATE TABLE posts (
post_id         INT(8) NOT NULL AUTO_INCREMENT,
topic_subject   VARCHAR(255) NOT NULL,
post_content        TEXT NOT NULL,
post_date       DATETIME NOT NULL,
post_by     INT(8) NOT NULL,
PRIMARY KEY (post_id)
);

CREATE TABLE adminposts (
post_id         INT(8) NOT NULL AUTO_INCREMENT,
topic_subject   VARCHAR(255) NOT NULL,
post_content        TEXT NOT NULL,
post_date       DATETIME NOT NULL,
post_by     INT(8) NOT NULL,
PRIMARY KEY (post_id)
);

INSERT INTO availableStudent(student_id) VALUES (01675386);
INSERT INTO topics(topic_subject, topic_by) VALUES ("Finals", '01675386')