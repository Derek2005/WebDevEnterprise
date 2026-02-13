CREATE TABLE IF NOT EXISTS mail (
    id SERIAL PRIMARY KEY,
    subject TEXT NOT NULL,
    body TEXT NOT NULL
);

-- Optional default record
INSERT INTO mail (subject, body)
VALUES ('Welcome!', 'This is your first mail.')
ON CONFLICT DO NOTHING;
