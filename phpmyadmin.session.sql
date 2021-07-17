
--@block
CREATE INDEX title_index ON movies(title);

--@block
ALTER TABLE movie_genres
ADD FOREIGN KEY (movie_id) REFERENCES movies(id);

--@BLOCK
ALTER TABLE movie_genres
ADD FOREIGN KEY (genre_id) REFERENCES genres(id);
