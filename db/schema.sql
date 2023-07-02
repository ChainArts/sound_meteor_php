CREATE TABLE IF NOT EXISTS users (
    user_id SERIAL PRIMARY KEY NOT NULL,
    email TEXT NOT NULL UNIQUE,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS genres (
    genre_id SERIAL PRIMARY KEY NOT NULL,
    name TEXT
);

CREATE TABLE IF NOT EXISTS moods (
    mood_id SERIAL PRIMARY KEY NOT NULL,
    name TEXT,
    emotion TEXT
);

CREATE TABLE IF NOT EXISTS genre_mood_relations (
    genre_id int,
    mood_id int,
    CONSTRAINT genre_id FOREIGN KEY (genre_id) REFERENCES genres(genre_id) ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT mood_id FOREIGN KEY (mood_id) REFERENCES moods(mood_id) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS playlists (
    playlist_id SERIAL PRIMARY KEY NOT NULL,
    name TEXT,
    creator_id int,
    genre_id int,
    CONSTRAINT creator_id FOREIGN KEY (creator_id) REFERENCES users(user_id) ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT genre_id FOREIGN KEY (genre_id) REFERENCES genres(genre_id) ON UPDATE CASCADE ON DELETE SET NULL,
    view_amount bigint DEFAULT 0,
    isshared BOOL NOT NULL DEFAULT FALSE
);

CREATE TABLE IF NOT EXISTS savedplaylists (
    user_id int,
    playlist_id int,
    CONSTRAINT user_id FOREIGN KEY (user_id) REFERENCES users(user_id) ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT playlist_id FOREIGN KEY (playlist_id) REFERENCES playlists(playlist_id) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS user_pref_genre(
    user_id int,
    genre_id int,
    CONSTRAINT user_id FOREIGN KEY (user_id) REFERENCES users(user_id) ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT genre_id FOREIGN KEY (genre_id) REFERENCES genres(genre_id) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS user_pref_mood(
    user_id int,
    mood_id int,
    CONSTRAINT user_id FOREIGN KEY (user_id) REFERENCES users(user_id) ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT mood_id FOREIGN KEY (mood_id) REFERENCES moods(mood_id) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS user_pref_gen(
    user_id int UNIQUE,
    oldest_track_year SMALLINT default 1980,
    playlist_length SMALLINT default 3,
    mood_weight_percentage SMALLINT default 50,
    CONSTRAINT user_id FOREIGN KEY (user_id) REFERENCES users(user_id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS tracks (
    track_id SERIAL PRIMARY KEY NOT NULL,
    title TEXT NOT NULL,
    ytlink TEXT,
    sclink TEXT,
    discogs TEXT,
    albumcoverlink TEXT default './media/fallback_img',
    year int
);

CREATE TABLE IF NOT EXISTS track_is_genre(
    track_id int,
    genre_id int,
    CONSTRAINT track_id FOREIGN KEY (track_id) REFERENCES tracks(track_id) ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT genre_id FOREIGN KEY (genre_id) REFERENCES genres(genre_id) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS track_in_playlist (
    playlist_id int,
    track_id int,
    CONSTRAINT playlist_id FOREIGN KEY (playlist_id) REFERENCES playlists(playlist_id) ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT track_id FOREIGN KEY (track_id) REFERENCES tracks(track_id) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS reset_password (
    user_id int,
    email TEXT NOT NULL,
    res_code TEXT NOT NULL,
    CONSTRAINT email FOREIGN KEY (email) REFERENCES users(email) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT user_id FOREIGN KEY (user_id) REFERENCES users(user_id) ON UPDATE CASCADE ON DELETE SET NULL
);

INSERT INTO users (user_id, email, username, password) VALUES (default, 'maxroll54@gmail.com', 'ChainArts', '$2a$10$.IBLY.YPfb3Bm.cPidIVbu.07BuYeggf.LF56a66wagJAukluYadK');
INSERT INTO users (user_id, email, username, password) VALUES (default, 'sandra.scheipl@gmail.com', 'Citysa', '$2a$10$7kfXe0Y92n6xR4Dxd/PaJuLMsgn1x39.LzOakpuuK17w/AXK/QN8S');
INSERT INTO users (user_id, email, username, password) VALUES (default, 'marion.roll@aon.at', 'Maroan', '$2a$10$RK75b7kGHdtkCsfTHXVBFeE4Ers8cS246hGyH9OIt/P/vMujrwNle');
INSERT INTO users (user_id, email, username, password) VALUES (default, 'andreas.roll@aon.at', 'Hαndy', '$2a$10$wtsQFuVgeggrkNvnTgA2FOEdFo12N7RA5DgF/FaVOvHRw4TezS.rG');
INSERT INTO users (user_id, email, username, password) VALUES (default, 'admin@soundmeteor.at', 'admin', '$2a$10$cF14/tv5/JYCMgL5L80iYeUWGwoSP.FDb9zNnw/2bmh7EX5A/OL3a');
INSERT INTO users (user_id, email, username, password) VALUES (default, 'brigitte.jellinek@fh-salzburg.ac.at', 'bjellinek', '$2a$10$EbNapa.QyyKZIS1ZEz.HZO2H5tNCBW4AnA9tRKrPcytcp4QgGvKda');
INSERT INTO users (user_id, email, username, password) VALUES (default, 'marion.menzel@fh-salzburg.ac.at', 'mrnmnzl', ' $2a$10$NI/p.zbFnFdlZ/hz0PsRLOqgevsReJY5K60S2Ud4nQfEShHAnok2G ');

-- Electronic
INSERT INTO genres (name) VALUES
    ('Techno'),
    ('House'),
    ('Trance'),
    ('Drum n Bass'),
    ('Dubstep'),
    ('Ambient'),
    ('Breakbeat'),
    ('IDM'),
    ('Electro'),
    ('Acid'),
    ('Progressive House'),
    ('Minimal Techno'),
    ('UK Garage'),
    ('Jungle'),
    ('Synthwave'),
    ('Downtempo'),
    ('Hardcore Techno'),
    ('Deep House'),
    ('Electroswing'),
    ('Vaporwave'),
    ('Chillout'),
    ('Nu-Disco'),
    ('Industrial'),
    ('Progressive Trance'),
    ('Tech House'),
    ('Future Garage'),
    ('Hardstyle'),
    ('Future Bass'),
    ('Chillwave'),
    ('Glitch Hop');

-- Rock
INSERT INTO genres (name) VALUES
    ('Alternative Rock'),
    ('Classic Rock'),
    ('Indie Rock'),
    ('Punk'),
    ('Metal'),
    ('Progressive Rock'),
    ('Grunge'),
    ('Psychedelic Rock'),
    ('Garage Rock'),
    ('Post-Rock'),
    ('Hard Rock'),
    ('Folk Rock'),
    ('Pop Rock'),
    ('Stoner Rock'),
    ('Blues Rock'),
    ('Art Rock'),
    ('Surf Rock'),
    ('Glam Rock'),
    ('Industrial Rock'),
    ('Math Rock'),
    ('Experimental Rock'),
    ('Shoegaze'),
    ('Noise Rock'),
    ('Funk Rock'),
    ('Rock n Roll'),
    ('Punk Rock'),
    ('Emo'),
    ('Gothic Rock'),
    ('Southern Rock'),
    ('New Wave');

-- Pop
INSERT INTO genres (name) VALUES
    ('Synthpop'),
    ('Electropop'),
    ('Dance-Pop'),
    ('Power Pop'),
    ('Indie Pop'),
    ('Bubblegum Pop'),
    ('Sophisti-Pop'),
    ('J-Pop'),
    ('K-Pop'),
    ('Dream Pop'),
    ('R&B Pop'),
    ('Disco Pop'),
    ('Country Pop'),
    ('Baroque Pop'),
    ('Teen Pop'),
    ('Latin Pop'),
    ('Folk Pop'),
    ('Europop'),
    ('Afrobeat'),
    ('Experimental Pop'),
    ('Synthwave'),
    ('Adult Contemporary'),
    ('Britpop'),
    ('Chamber Pop'),
    ('Electronic Pop'),
    ('Orchestral Pop'),
    ('Sunshine Pop'),
    ('Soft Rock'),
    ('Vocal Pop'),
    ('Tropical Pop');

-- Hip Hop
INSERT INTO genres (name) VALUES
    ('Rap'),
    ('Boom Bap'),
    ('Trap'),
    ('East Coast Hip Hop'),
    ('West Coast Hip Hop'),
    ('R&B'),
    ('Conscious Hip Hop'),
    ('Experimental Hip Hop'),
    ('Lo-Fi Hip Hop'),
    ('Afrobeat'),
    ('Gangsta Rap'),
    ('Jazz Rap'),
    ('G-Funk'),
    ('Crunk'),
    ('Cloud Rap'),
    ('Mumble Rap'),
    ('Alternative Hip Hop'),
    ('Old School Hip Hop'),
    ('Underground Hip Hop'),
    ('Turntablism'),
    ('Southern Hip Hop'),
    ('Trap Metal'),
    ('Trap Soul'),
    ('UK Hip Hop'),
    ('Pop Rap'),
    ('Hyphy'),
    ('Drill'),
    ('Grime'),
    ('Dirty South'),
    ('Neo-Soul');

-- Reggae
INSERT INTO genres (name) VALUES
    ('Roots Reggae'),
    ('Dancehall'),
    ('Dub'),
    ('Ska'),
    ('Rocksteady'),
    ('Reggae Fusion'),
    ('Lovers Rock'),
    ('Ragga'),
    ('Reggae-Pop');

-- Funk / Soul
INSERT INTO genres (name) VALUES
    ('Funk'),
    ('Soul'),
    ('Rhythm and Blues'),
    ('Disco'),
    ('Motown'),
    ('Neo-Soul'),
    ('Northern Soul'),
    ('Deep Funk'),
    ('Soul Jazz'),
    ('Funk Rock');

-- Classical
INSERT INTO genres (name) VALUES
    ('Baroque'),
    ('Classical Period'),
    ('Romantic'),
    ('Modern'),
    ('Minimalism'),
    ('Chamber Music'),
    ('Opera'),
    ('Choral'),
    ('Symphonic'),
    ('Contemporary');

INSERT INTO moods (name) VALUES
    ('Energetic'),
    ('Groovy'),
    ('Euphoric'),
    ('Intense'),
    ('Gritty'),
    ('Calming'),
    ('Experimental'),
    ('Robotic'),
    ('Trippy'),
    ('Melodic'),
    ('Minimalistic'),
    ('Nostalgic'),
    ('Relaxing'),
    ('Soulful'),
    ('Upbeat'),
    ('Dreamy'),
    ('Funky'),
    ('Atmospheric'),
    ('Hard-hitting'),
    ('Lush'),
    ('Quirky'),
    ('Vibey'),
    ('Depressing'),
    ('Aggressive');


-- Techno
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (1, 1),  -- Techno - Energetic
    (1, 2);  -- Techno - Groovy

-- House
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (2, 2),  -- House - Groovy
    (2, 6);  -- House - Calming

-- Trance
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (3, 3),  -- Trance - Euphoric
    (3, 4);  -- Trance - Intense

-- Drum and Bass
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (4, 1),  -- Drum and Bass - Energetic
    (4, 4);  -- Drum and Bass - Intense

-- Dubstep
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (5, 4),  -- Dubstep - Intense
    (5, 9);  -- Dubstep - Trippy

-- Ambient
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (6, 6),  -- Ambient - Calming
    (6, 11); -- Ambient - Nostalgic

-- Breakbeat
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (7, 1),  -- Breakbeat - Energetic
    (7, 7);  -- Breakbeat - Experimental

-- IDM
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (8, 7),  -- IDM - Experimental
    (8, 11); -- IDM - Nostalgic

-- Electro
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (9, 1),  -- Electro - Energetic
    (9, 9);  -- Electro - Trippy

-- Acid
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (10, 1), -- Acid - Energetic
    (10, 9); -- Acid - Trippy

-- Progressive House
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (11, 6), -- Progressive House - Calming
    (11, 9); -- Progressive House - Trippy

-- Minimal Techno
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (12, 6), -- Minimal Techno - Calming
    (12, 11);-- Minimal Techno - Nostalgic

-- UK Garage
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (13, 2), -- UK Garage - Groovy
    (13, 6); -- UK Garage - Calming

-- Jungle
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (14, 1), -- Jungle - Energetic
    (14, 9); -- Jungle - Trippy

-- Synthwave
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (15, 11),-- Synthwave - Nostalgic
    (15, 13);-- Synthwave - Relaxing

-- Downtempo
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (16, 6), -- Downtempo - Calming
    (16, 13);-- Downtempo - Relaxing

-- Hardcore Techno
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (17, 1), -- Hardcore Techno - Energetic
    (17, 4); -- Hardcore Techno - Intense

-- Deep House
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (18, 6), -- Deep House - Calming
    (18, 13);-- Deep House - Relaxing

-- Electroswing
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (19, 2), -- Electroswing - Groovy
    (19, 11);-- Electroswing - Nostalgic

-- Vaporwave
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (20, 11),-- Vaporwave - Nostalgic
    (20, 13);-- Vaporwave - Relaxing

-- Chillout
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (21, 6), -- Chillout - Calming
    (21, 13);-- Chillout - Relaxing

-- Nu-Disco
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (22, 2), -- Nu-Disco - Groovy
    (22, 13);-- Nu-Disco - Relaxing

-- Industrial
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (23, 5), -- Industrial - Gritty
    (23, 21);-- Industrial - Quirky

-- Progressive Trance
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (24, 3), -- Progressive Trance - Euphoric
    (24, 4); -- Progressive Trance - Intense

-- Tech House
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (25, 2), -- Tech House - Groovy
    (25, 6); -- Tech House - Calming

-- Future Garage
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (26, 2), -- Future Garage - Groovy
    (26, 6); -- Future Garage - Calming

-- Hardstyle
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (27, 1), -- Hardstyle - Energetic
    (27, 4); -- Hardstyle - Intense

-- Future Bass
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (28, 1), -- Future Bass - Energetic
    (28, 9); -- Future Bass - Trippy

-- Chillwave
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (29, 6), -- Chillwave - Calming
    (29, 11);-- Chillwave - Nostalgic

-- Glitch Hop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (30, 2), -- Glitch Hop - Groovy
    (30, 7); -- Glitch Hop - Experimental

-- Alternative Rock
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (31, 2),   -- Alternative Rock - Groovy
    (31, 13);  -- Alternative Rock - Upbeat

-- Classic Rock
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (32, 13),  -- Classic Rock - Upbeat
    (32, 18);  -- Classic Rock - Hard-hitting

-- Indie Rock
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (33, 13),  -- Indie Rock - Upbeat
    (33, 16);  -- Indie Rock - Atmospheric

-- Punk
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (34, 1),   -- Punk - Energetic
    (34, 18);  -- Punk - Hard-hitting

-- Metal
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (35, 4),   -- Metal - Intense
    (35, 18);  -- Metal - Hard-hitting

-- Progressive Rock
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (36, 7),   -- Progressive Rock - Experimental
    (36, 11);  -- Progressive Rock - Nostalgic

-- Grunge
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (37, 9),   -- Grunge - Trippy
    (37, 11);  -- Grunge - Nostalgic

-- Psychedelic Rock
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (38, 9),   -- Psychedelic Rock - Trippy
    (38, 11);  -- Psychedelic Rock - Nostalgic

-- Garage Rock
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (39, 2),   -- Garage Rock - Groovy
    (39, 13);  -- Garage Rock - Upbeat

-- Post-Rock
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (40, 11), -- Post-Rock - Nostalgic
    (40, 16); -- Post-Rock - Atmospheric

-- Hard Rock
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (41, 1),  -- Hard Rock - Energetic
    (41, 18); -- Hard Rock - Hard-hitting

-- Folk Rock
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (42, 13), -- Folk Rock - Upbeat
    (42, 16); -- Folk Rock - Atmospheric

-- Pop Rock
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (43, 13), -- Pop Rock - Upbeat
    (43, 16); -- Pop Rock - Atmospheric

-- Stoner Rock
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (44, 9),  -- Stoner Rock - Trippy
    (44, 18); -- Stoner Rock - Hard-hitting

-- Blues Rock
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (45, 13), -- Blues Rock - Upbeat
    (45, 16); -- Blues Rock - Atmospheric

-- Art Rock
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (46, 7),  -- Art Rock - Experimental
    (46, 16); -- Art Rock - Atmospheric

-- Surf Rock
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (47, 2),  -- Surf Rock - Groovy
    (47, 13); -- Surf Rock - Upbeat

-- Glam Rock
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (48, 2),  -- Glam Rock - Groovy
    (48, 16); -- Glam Rock - Atmospheric

-- Industrial Rock
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (49, 5),  -- Industrial Rock - Gritty
    (49, 7);  -- Industrial Rock - Experimental

-- Math Rock
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (50, 7),  -- Math Rock - Experimental
    (50, 18); -- Math Rock - Hard-hitting

-- Experimental Rock
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (51, 7),  -- Experimental Rock - Experimental
    (51, 16); -- Experimental Rock - Atmospheric

-- Shoegaze
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (52, 11), -- Shoegaze - Nostalgic
    (52, 16); -- Shoegaze - Atmospheric

-- Noise Rock
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (53, 5),  -- Noise Rock - Gritty
    (53, 7);  -- Noise Rock - Experimental

-- Funk Rock
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (54, 2),  -- Funk Rock - Groovy
    (54, 18); -- Funk Rock - Hard-hitting

-- Rock n Roll
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (55, 9),  -- Rock n Roll - Trippy
    (55, 11); -- Rock n Roll - Nostalgic

-- Punk Rock
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (56, 1),  -- Punk Rock - Energetic
    (56, 18); -- Punk Rock - Hard-hitting

-- Emo
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (57, 11), -- Emo - Nostalgic
    (57, 14); -- Emo - Soulful

-- Gothic Rock
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (58, 11), -- Gothic Rock - Nostalgic
    (58, 14); -- Gothic Rock - Soulful

-- Southern Rock
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (59, 13), -- Southern Rock - Upbeat
    (59, 18); -- Southern Rock - Hard-hitting

-- New Wave
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (60, 2),  -- New Wave - Groovy
    (60, 16); -- New Wave - Atmospheric

-- Synthpop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (61, 11),  -- Synthpop - Nostalgic
    (61, 16);  -- Synthpop - Atmospheric

-- Electropop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (62, 11),  -- Electropop - Nostalgic
    (62, 16);  -- Electropop - Atmospheric

-- Dance-Pop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (63, 2),   -- Dance-Pop - Groovy
    (63, 13);  -- Dance-Pop - Upbeat

-- Power Pop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (64, 2),   -- Power Pop - Groovy
    (64, 16);  -- Power Pop - Atmospheric

-- Indie Pop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (65, 16),  -- Indie Pop - Atmospheric
    (65, 18);  -- Indie Pop - Hard-hitting

-- Bubblegum Pop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (66, 1),   -- Bubblegum Pop - Energetic
    (66, 13);  -- Bubblegum Pop - Upbeat

-- Sophisti-Pop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (67, 11),  -- Sophisti-Pop - Nostalgic
    (67, 14);  -- Sophisti-Pop - Soulful

-- J-Pop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (68, 13),  -- J-Pop - Upbeat
    (68, 16);  -- J-Pop - Atmospheric

-- K-Pop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (69, 13),  -- K-Pop - Upbeat
    (69, 14);  -- K-Pop - Soulful

-- Dream Pop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (70, 11), -- Dream Pop - Nostalgic
    (70, 16); -- Dream Pop - Atmospheric

-- R&B Pop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (71, 14), -- R&B Pop - Soulful
    (71, 18); -- R&B Pop - Hard-hitting

-- Disco Pop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (72, 2),  -- Disco Pop - Groovy
    (72, 13); -- Disco Pop - Upbeat

-- Country Pop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (73, 13), -- Country Pop - Upbeat
    (73, 16); -- Country Pop - Atmospheric

-- Baroque Pop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (74, 11), -- Baroque Pop - Nostalgic
    (74, 16); -- Baroque Pop - Atmospheric

-- Teen Pop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (75, 1),  -- Teen Pop - Energetic
    (75, 13); -- Teen Pop - Upbeat

-- Latin Pop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (76, 13), -- Latin Pop - Upbeat
    (76, 14); -- Latin Pop - Soulful

-- Folk Pop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (77, 11), -- Folk Pop - Nostalgic
    (77, 16); -- Folk Pop - Atmospheric

-- Europop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (78, 2),  -- Europop - Groovy
    (78, 13); -- Europop - Upbeat

-- Afrobeat
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (79, 13), -- Afrobeat - Upbeat
    (79, 18); -- Afrobeat - Hard-hitting

-- Experimental Pop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (80, 7),  -- Experimental Pop - Experimental
    (80, 16); -- Experimental Pop - Atmospheric

-- Synthwave
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (81, 11), -- Synthwave - Nostalgic
    (81, 16); -- Synthwave - Atmospheric

-- Adult Contemporary
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (82, 14), -- Adult Contemporary - Soulful
    (82, 16); -- Adult Contemporary - Atmospheric

-- Britpop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (83, 11), -- Britpop - Nostalgic
    (83, 16); -- Britpop - Atmospheric

-- Chamber Pop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (84, 11), -- Chamber Pop - Nostalgic
    (84, 14); -- Chamber Pop - Soulful

-- Electronic Pop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (85, 2),  -- Electronic Pop - Groovy
    (85, 16); -- Electronic Pop - Atmospheric

-- Orchestral Pop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (86, 11), -- Orchestral Pop - Nostalgic
    (86, 16); -- Orchestral Pop - Atmospheric

-- Sunshine Pop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (87, 13), -- Sunshine Pop - Upbeat
    (87, 16); -- Sunshine Pop - Atmospheric

-- Soft Rock
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (88, 11), -- Soft Rock - Nostalgic
    (88, 14); -- Soft Rock - Soulful

-- Vocal Pop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (89, 14), -- Vocal Pop - Soulful
    (89, 16); -- Vocal Pop - Atmospheric

-- Tropical Pop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (90, 13), -- Tropical Pop - Upbeat
    (90, 16); -- Tropical Pop - Atmospheric

-- Rap
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (91, 4),  -- Rap - Intense
    (91, 18); -- Rap - Hard-hitting

-- Boom Bap
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (92, 4),  -- Boom Bap - Intense
    (92, 18); -- Boom Bap - Hard-hitting

-- Trap
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (93, 4),  -- Trap - Intense
    (93, 18); -- Trap - Hard-hitting

-- East Coast Hip Hop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (94, 4),  -- East Coast Hip Hop - Intense
    (94, 18); -- East Coast Hip Hop - Hard-hitting

-- West Coast Hip Hop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (95, 4),  -- West Coast Hip Hop - Intense
    (95, 18); -- West Coast Hip Hop - Hard-hitting

-- R&B
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (96, 14), -- R&B - Soulful
    (96, 16); -- R&B - Atmospheric

-- Conscious Hip Hop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (97, 14), -- Conscious Hip Hop - Soulful
    (97, 18); -- Conscious Hip Hop - Hard-hitting

-- Experimental Hip Hop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (98, 7),  -- Experimental Hip Hop - Experimental
    (98, 16); -- Experimental Hip Hop - Atmospheric

-- Lo-Fi Hip Hop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (99, 11), -- Lo-Fi Hip Hop - Nostalgic
    (99, 16); -- Lo-Fi Hip Hop - Atmospheric

-- Afrobeat
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (100, 13), -- Afrobeat - Upbeat
    (100, 18); -- Afrobeat - Hard-hitting

-- Gangsta Rap
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (101, 4),  -- Gangsta Rap - Intense
    (101, 18); -- Gangsta Rap - Hard-hitting

-- Jazz Rap
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (102, 14), -- Jazz Rap - Soulful
    (102, 18); -- Jazz Rap - Hard-hitting

-- G-Funk
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (103, 1),  -- G-Funk - Energetic
    (103, 18); -- G-Funk - Hard-hitting

-- Crunk
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (104, 2),  -- Crunk - Groovy
    (104, 18); -- Crunk - Hard-hitting

-- Cloud Rap
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (105, 4),  -- Cloud Rap - Intense
    (105, 16); -- Cloud Rap - Atmospheric

-- Mumble Rap
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (106, 4),  -- Mumble Rap - Intense
    (106, 18); -- Mumble Rap - Hard-hitting

-- Alternative Hip Hop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (107, 11), -- Alternative Hip Hop - Nostalgic
    (107, 16); -- Alternative Hip Hop - Atmospheric

-- Old School Hip Hop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (108, 11), -- Old School Hip Hop - Nostalgic
    (108, 16); -- Old School Hip Hop - Atmospheric

-- Underground Hip Hop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (109, 11), -- Underground Hip Hop - Nostalgic
    (109, 18); -- Underground Hip Hop - Hard-hitting

-- Turntablism
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (110, 2),  -- Turntablism - Groovy
    (110, 16); -- Turntablism - Atmospheric

-- Southern Hip Hop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (111, 4),  -- Southern Hip Hop - Intense
    (111, 18); -- Southern Hip Hop - Hard-hitting

-- Trap Metal
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (112, 4),  -- Trap Metal - Intense
    (112, 18); -- Trap Metal - Hard-hitting

-- Trap Soul
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (113, 14), -- Trap Soul - Soulful
    (113, 16); -- Trap Soul - Atmospheric

-- UK Hip Hop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (114, 4),  -- UK Hip Hop - Intense
    (114, 18); -- UK Hip Hop - Hard-hitting

-- Pop Rap
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (115, 4),  -- Pop Rap - Intense
    (115, 18); -- Pop Rap - Hard-hitting

-- Hyphy
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (116, 1),  -- Hyphy - Energetic
    (116, 18); -- Hyphy - Hard-hitting

-- Drill
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (117, 4),  -- Drill - Intense
    (117, 18); -- Drill - Hard-hitting

-- Grime
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (118, 4),  -- Grime - Intense
    (118, 18); -- Grime - Hard-hitting

-- Dirty South
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (119, 4),  -- Dirty South - Intense
    (119, 18); -- Dirty South - Hard-hitting

-- Neo-Soul
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (120, 14), -- Neo-Soul - Soulful
    (120, 1);  -- Neo-Soul - Energetic

-- Roots Reggae
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (121, 14), -- Roots Reggae - Soulful
    (121, 16); -- Roots Reggae - Atmospheric

-- Dancehall
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (122, 2),  -- Dancehall - Groovy
    (122, 18); -- Dancehall - Hard-hitting

-- Dub
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (123, 16), -- Dub - Atmospheric
    (123, 7);  -- Dub - Experimental

-- Ska
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (124, 1),  -- Ska - Energetic
    (124, 14); -- Ska - Soulful

-- Rocksteady
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (125, 14), -- Rocksteady - Soulful
    (125, 16); -- Rocksteady - Atmospheric

-- Reggae Fusion
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (126, 1),  -- Reggae Fusion - Energetic
    (126, 14); -- Reggae Fusion - Soulful

-- Lovers Rock
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (127, 14), -- Lovers Rock - Soulful
    (127, 16); -- Lovers Rock - Atmospheric

-- Ragga
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (128, 2),  -- Ragga - Groovy
    (128, 18); -- Ragga - Hard-hitting

-- Reggae-Pop
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (129, 14), -- Reggae-Pop - Soulful
    (129, 16); -- Reggae-Pop - Atmospheric

-- Funk
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (130, 11), -- Funk - Nostalgic
    (130, 18); -- Funk - Hard-hitting

-- Soul
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (131, 14), -- Soul - Soulful
    (131, 11); -- Soul - Nostalgic

-- Rhythm and Blues
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (132, 14), -- Rhythm and Blues - Soulful
    (132, 11); -- Rhythm and Blues - Nostalgic

-- Disco
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (133, 1),  -- Disco - Energetic
    (133, 11); -- Disco - Nostalgic

-- Motown
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (134, 14), -- Motown - Soulful
    (134, 11); -- Motown - Nostalgic

-- Neo-Soul
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (135, 14), -- Neo-Soul - Soulful
    (135, 11); -- Neo-Soul - Nostalgic

-- Northern Soul
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (136, 14), -- Northern Soul - Soulful
    (136, 11); -- Northern Soul - Nostalgic

-- Deep Funk
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (137, 1),  -- Deep Funk - Energetic
    (137, 11); -- Deep Funk - Nostalgic

-- Soul Jazz
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (138, 14), -- Soul Jazz - Soulful
    (138, 11); -- Soul Jazz - Nostalgic

-- Funk Rock
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (139, 1),  -- Funk Rock - Energetic
    (139, 18); -- Funk Rock - Hard-hitting

-- Baroque
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (140, 11), -- Baroque - Nostalgic
    (140, 18); -- Baroque - Hard-hitting

-- Classical Period
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (141, 14), -- Classical Period - Soulful
    (141, 11); -- Classical Period - Nostalgic

-- Romantic
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (142, 14), -- Romantic - Soulful
    (142, 11); -- Romantic - Nostalgic

-- Modern
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (143, 11), -- Modern - Nostalgic
    (143, 7);  -- Modern - Experimental

-- Minimalism
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (144, 7),  -- Minimalism - Experimental
    (144, 11); -- Minimalism - Nostalgic

-- Chamber Music
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (145, 14), -- Chamber Music - Soulful
    (145, 11); -- Chamber Music - Nostalgic

-- Opera
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (146, 14), -- Opera - Soulful
    (146, 11); -- Opera - Nostalgic

-- Choral
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (147, 14), -- Choral - Soulful
    (147, 11); -- Choral - Nostalgic

-- Symphonic
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (148, 14), -- Symphonic - Soulful
    (148, 11); -- Symphonic - Nostalgic

-- Contemporary
INSERT INTO genre_mood_relations (genre_id, mood_id) VALUES
    (149, 7),  -- Contemporary - Experimental
    (149, 11); -- Contemporary - Nostalgic


INSERT INTO playlists(playlist_id, name, creator_id, view_amount, isshared) VALUES (default, 'Drum & Bass', 1, 723, true);
INSERT INTO playlists(playlist_id, name, creator_id, view_amount, isshared) VALUES (default, 'Lieblingslieder', 3, 512, true);
INSERT INTO playlists(playlist_id, name, creator_id, view_amount, isshared) VALUES (default, 'Music', 2, 1294, true);
INSERT INTO playlists(playlist_id, name, creator_id, view_amount, isshared) VALUES (default, 'Arbeit', 4, 1294, default);

INSERT INTO savedplaylists (user_id, playlist_id) VALUES (1, 1);
INSERT INTO savedplaylists (user_id, playlist_id) VALUES (3, 2);
INSERT INTO savedplaylists (user_id, playlist_id) VALUES (2, 3);
INSERT INTO savedplaylists (user_id, playlist_id) VALUES (1, 3);
INSERT INTO savedplaylists (user_id, playlist_id) VALUES (3, 4);

INSERT INTO user_pref_genre(user_id, genre_id) VALUES (1, 4);
INSERT INTO user_pref_genre(user_id, genre_id) VALUES (2, 7);
INSERT INTO user_pref_genre(user_id, genre_id) VALUES (3, 6);
INSERT INTO user_pref_genre(user_id, genre_id) VALUES (4, 6);

INSERT INTO user_pref_mood(user_id, mood_id) VALUES (1, 1);
INSERT INTO user_pref_mood(user_id, mood_id) VALUES (2, 2);
INSERT INTO user_pref_mood(user_id, mood_id) VALUES (3, 5);
INSERT INTO user_pref_mood(user_id, mood_id) VALUES (4, 5);

INSERT INTO user_pref_gen(user_id, oldest_track_year, playlist_length, mood_weight_percentage) VALUES (1, 2010, default, default);
INSERT INTO user_pref_gen(user_id, oldest_track_year, playlist_length, mood_weight_percentage) VALUES (2, 2000, default, default);
INSERT INTO user_pref_gen(user_id, oldest_track_year, playlist_length, mood_weight_percentage) VALUES (3, default, default, default);
INSERT INTO user_pref_gen(user_id, oldest_track_year, playlist_length, mood_weight_percentage) VALUES (4, default, default, default);
INSERT INTO user_pref_gen(user_id, oldest_track_year, playlist_length, mood_weight_percentage) VALUES (5, 2005, default, default);
INSERT INTO user_pref_gen(user_id, oldest_track_year, playlist_length, mood_weight_percentage) VALUES (6, 1980, default, default);
INSERT INTO user_pref_gen(user_id, oldest_track_year, playlist_length, mood_weight_percentage) VALUES (7, 1990, default, default);

INSERT INTO tracks VALUES (default, 'SubFocus - Solar System', 'https://www.youtube.com/results?search_query=SubFocus+-+Solar+System', 'https://soundcloud.com/search?q=sub focus - solar system','https://www.discogs.com/release/19111147-Sub-Focus-Siren-Solar-System', NULL, 2019);
INSERT INTO tracks VALUES (default, 'Delta Heavy - Against the Tide (ft. Lauren L''aimant)', 'https://www.youtube.com/results?search_query=Delta+Heavy+-+Against+the+Tide+(ft.+Lauren+L%27aimant)', NULL, NUll, NULL, 2023);
INSERT INTO tracks VALUES (default, 'Blaine Stranger & Solomon France ft. Venjent - Rewind', NULL, NULL, NULL, NULL, 2023);
INSERT INTO tracks VALUES (default, 'Skillet - Legendary', NULL, NULL, NULL, NULL, 2019);
INSERT INTO tracks VALUES (default, 'Skillet - Feel Invincible', NULL, NULL, NULL, NULL, 2016);
INSERT INTO tracks VALUES (default, 'Halestorm - Love Bites (So Do I)', NULL, NULL, NULL, NULL, 2012);
INSERT INTO tracks VALUES (default, 'Snow Patrol - Chasing Cars', NULL, NULL, NULL, NULL, 2006);
INSERT INTO tracks VALUES (default, 'The Pussycat Dolls - Sway', NULL, NULL, NULL, NULL, 2004);
INSERT INTO tracks VALUES (default, 'Zoey Wees - Control', NULL, NULL, NULL, NULL, 2020);
INSERT INTO tracks VALUES (default, 'Slash - Back from Cali (Feat. Myles Kennedy)', NULL, NULL, NULL, NULL, 2010);
INSERT INTO tracks VALUES (default, 'Dire Straits - Money for Nothing', NULL, NULL, NULL, NULL, 1988);
INSERT INTO tracks VALUES (default, 'Bryan Adams - Run to you', NULL, NULL, NULL, NULL, 1984);

INSERT INTO track_is_genre(track_id, genre_id) VALUES(1, 4);
INSERT INTO track_is_genre(track_id, genre_id) VALUES(2, 4);
INSERT INTO track_is_genre(track_id, genre_id) VALUES(3, 4);
INSERT INTO track_is_genre(track_id, genre_id) VALUES(4, 31);
INSERT INTO track_is_genre(track_id, genre_id) VALUES(5, 31);
INSERT INTO track_is_genre(track_id, genre_id) VALUES(6, 31);
INSERT INTO track_is_genre(track_id, genre_id) VALUES(8, 43);
INSERT INTO track_is_genre(track_id, genre_id) VALUES(8, 106);
INSERT INTO track_is_genre(track_id, genre_id) VALUES(9, 78);
INSERT INTO track_is_genre(track_id, genre_id) VALUES(10, 41);
INSERT INTO track_is_genre(track_id, genre_id) VALUES(11, 45);
INSERT INTO track_is_genre(track_id, genre_id) VALUES(12, 55);


INSERT INTO track_in_playlist(playlist_id, track_id) VALUES (1, 1);
INSERT INTO track_in_playlist(playlist_id, track_id) VALUES (1, 2);
INSERT INTO track_in_playlist(playlist_id, track_id) VALUES (1, 3);
INSERT INTO track_in_playlist(playlist_id, track_id) VALUES (3, 4);
INSERT INTO track_in_playlist(playlist_id, track_id) VALUES (3, 5);
INSERT INTO track_in_playlist(playlist_id, track_id) VALUES (3, 6);
INSERT INTO track_in_playlist(playlist_id, track_id) VALUES (2, 7);
INSERT INTO track_in_playlist(playlist_id, track_id) VALUES (2, 8);
INSERT INTO track_in_playlist(playlist_id, track_id) VALUES (2, 9);
INSERT INTO track_in_playlist(playlist_id, track_id) VALUES (4, 10);
INSERT INTO track_in_playlist(playlist_id, track_id) VALUES (4, 11);
INSERT INTO track_in_playlist(playlist_id, track_id) VALUES (4, 12);


